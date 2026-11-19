<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\DocxToHtml;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * "Import from Word" for the admin editors (Resource Library full text,
 * Blog main body). Converts an uploaded .docx to HTML on the server, stores
 * its pictures next to the editors' other images, and returns the HTML for
 * the editor. Nothing is saved to a paper or post here — the admin reviews
 * the result in the editor and saves as usual.
 */
class WordImportController extends Controller
{
    public const IMAGE_DIR = 'blog/content-images';

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100 MB
        ], [
            'file.max' => 'Word files up to 100 MB can be imported.',
        ]);

        $file = $request->file('file');

        if (strtolower($file->getClientOriginalExtension()) !== 'docx') {
            return response()->json([
                'message' => 'Only Word .docx files can be imported. Open older .doc files in Word and use Save As → Word Document (.docx).',
            ], 422);
        }

        $stored = [];

        try {
            $converter = new DocxToHtml(function (string $bytes, string $ext) use (&$stored) {
                $path = self::IMAGE_DIR . '/' . Str::random(40) . '.' . $ext;
                Storage::disk('public')->put($path, $bytes);
                $stored[] = $path;

                return Storage::url($path);
            });

            $result = $converter->convert($file->getRealPath());
        } catch (RuntimeException $e) {
            Storage::disk('public')->delete($stored);

            return response()->json(['message' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            Storage::disk('public')->delete($stored);
            Log::error('Word import failed', ['file' => $file->getClientOriginalName(), 'error' => $e->getMessage()]);

            return response()->json(['message' => 'This document could not be converted. Please try saving it again in Word, or send it to support.'], 500);
        }

        if (trim(strip_tags($result['html'], '<img>')) === '') {
            return response()->json(['message' => 'No text was found in this document.'], 422);
        }

        return response()->json($result);
    }
}
