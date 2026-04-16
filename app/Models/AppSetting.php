<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    // ── Defaults ─────────────────────────────────────────────────────────
    public const DEFAULTS = [
        'app_name'     => 'Kevin Thompson',
        'color_copper' => '#b5722a',
        'color_slate'  => '#1a2332',
        'app_icon'     => null,
        'favicon'      => null,
    ];

    // ── CRUD helpers ──────────────────────────────────────────────────────

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row?->value ?? $default ?? static::DEFAULTS[$key] ?? null;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    // ── Color utilities ───────────────────────────────────────────────────

    /**
     * Blend a hex color toward white by $amount (0.0 = original, 1.0 = white).
     */
    public static function lighten(string $hex, float $amount): string
    {
        [$r, $g, $b] = static::hexToRgb($hex);
        return sprintf('#%02x%02x%02x',
            min(255, (int) round($r + (255 - $r) * $amount)),
            min(255, (int) round($g + (255 - $g) * $amount)),
            min(255, (int) round($b + (255 - $b) * $amount)),
        );
    }

    /**
     * Blend a hex color toward black by $amount (0.0 = original, 1.0 = black).
     */
    public static function darken(string $hex, float $amount): string
    {
        [$r, $g, $b] = static::hexToRgb($hex);
        return sprintf('#%02x%02x%02x',
            max(0, (int) round($r * (1 - $amount))),
            max(0, (int) round($g * (1 - $amount))),
            max(0, (int) round($b * (1 - $amount))),
        );
    }

    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    /**
     * Resolve all color tokens from stored settings.
     * Returns the full set of CSS variable values for both admin and frontend.
     */
    public static function resolvedColors(): array
    {
        $copper = static::get('color_copper', static::DEFAULTS['color_copper']);
        $slate  = static::get('color_slate',  static::DEFAULTS['color_slate']);

        return [
            // Admin + frontend shared
            'copper'     => $copper,
            'copper2'    => static::lighten($copper, 0.15),
            'copper3'    => static::lighten($copper, 0.35),
            'slate'      => $slate,
            'slateHi'    => static::lighten($slate, 0.08),
            // Frontend-only extras
            'slate2'     => static::lighten($slate, 0.08),
            'slate3'     => static::lighten($slate, 0.18),
            'copper4'    => static::lighten($copper, 0.55),
            'copperDark' => static::darken($copper, 0.3),
        ];
    }
}
