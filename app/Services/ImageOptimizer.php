<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Lightweight, dependency-free image optimisation built on PHP's GD extension.
 *
 * Resizes oversized images down to a sane maximum and re-encodes them with
 * sensible compression so uploaded logos stay small and fast to serve. The
 * project ships no intervention/image library, so this keeps things simple and
 * fails gracefully — if GD is unavailable or the file can't be processed, the
 * original upload is left untouched.
 */
class ImageOptimizer
{
    /**
     * Optimise an image stored on the given disk (relative path).
     *
     * @param  string  $path  Path relative to the disk root (e.g. "clients/logo.jpg").
     * @param  int  $maxWidth  Maximum width in pixels; larger images are scaled down.
     * @param  int  $quality  JPEG/WebP quality (0–100).
     * @param  string  $disk  Storage disk name.
     */
    public static function optimize(string $path, int $maxWidth = 600, int $quality = 82, string $disk = 'public'): void
    {
        if (! function_exists('imagecreatetruecolor')) {
            return; // GD not installed — nothing we can do, leave original as-is.
        }

        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return;
        }

        $absolute = $storage->path($path);

        try {
            $info = @getimagesize($absolute);
            if ($info === false) {
                return; // Not a recognised image.
            }

            [$width, $height] = $info;
            $type = $info[2];

            // Map each image type to the GD reader/writer it needs. Skip cleanly
            // (no error) when this GD build lacks support for the codec — e.g. some
            // builds ship without JPEG/WebP. The original upload is left as-is.
            $codec = match ($type) {
                IMAGETYPE_JPEG => ['imagecreatefromjpeg', 'imagejpeg'],
                IMAGETYPE_PNG => ['imagecreatefrompng', 'imagepng'],
                IMAGETYPE_WEBP => ['imagecreatefromwebp', 'imagewebp'],
                IMAGETYPE_GIF => ['imagecreatefromgif', 'imagegif'],
                default => null,
            };

            if ($codec === null || ! function_exists($codec[0]) || ! function_exists($codec[1])) {
                return; // Unsupported / unavailable codec — keep the original.
            }

            $source = @$codec[0]($absolute);

            if (! $source) {
                return;
            }

            // Only downscale — never enlarge a smaller image.
            $scale = $width > $maxWidth ? $maxWidth / $width : 1.0;
            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));

            $canvas = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/WebP/GIF.
            if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP, IMAGETYPE_GIF], true)) {
                imagealphablending($canvas, false);
                imagesavealpha($canvas, true);
                $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            switch ($type) {
                case IMAGETYPE_PNG:
                    // PNG quality is 0 (best) – 9 (smallest); map roughly from $quality.
                    $pngLevel = (int) round((100 - $quality) / 11.1);
                    imagepng($canvas, $absolute, min(9, max(0, $pngLevel)));
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagewebp')) {
                        imagewebp($canvas, $absolute, $quality);
                    }
                    break;
                case IMAGETYPE_GIF:
                    imagegif($canvas, $absolute);
                    break;
                default:
                    imagejpeg($canvas, $absolute, $quality);
                    break;
            }

            imagedestroy($canvas);
            imagedestroy($source);
        } catch (\Throwable $e) {
            // Never let optimisation break an upload — silently keep the original.
            report($e);
        }
    }
}
