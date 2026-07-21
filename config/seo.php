<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Automatic SEO file generation
    |--------------------------------------------------------------------------
    |
    | When true, saving a Post, Service, Paper or SEO-related AppSetting
    | rewrites public/sitemap.xml and public/llms.txt via SeoGenerator.
    |
    | This is disabled in phpunit.xml: those hooks write real files into
    | public/, so a test run would otherwise bake fixture slugs and the local
    | APP_URL into the artifacts that get committed and deployed.
    |
    */

    'autogenerate' => env('SEO_AUTOGENERATE', true),

];
