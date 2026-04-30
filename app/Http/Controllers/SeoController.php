<?php

namespace App\Http\Controllers;

use App\Services\SeoGenerator;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        return response(file_get_contents(public_path('robots.txt')), 200, [
            'Content-Type'  => 'text/plain',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function sitemap(): Response
    {
        return response(file_get_contents(public_path('sitemap.xml')), 200, [
            'Content-Type'  => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function llms(): Response
    {
        return response(file_get_contents(public_path('llms.txt')), 200, [
            'Content-Type'  => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
