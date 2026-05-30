<?php

namespace App\Http\Controllers;

use App\Services\Captcha;
use Illuminate\Http\JsonResponse;

class CaptchaController extends Controller
{
    /**
     * Return a freshly minted challenge (SVG + signed token) as JSON.
     *
     * Used by the <x-captcha /> "refresh" button so a user can request a new
     * image without reloading the whole page.
     */
    public function refresh(): JsonResponse
    {
        return response()
            ->json(Captcha::issue())
            ->header('Cache-Control', 'no-store, max-age=0');
    }
}
