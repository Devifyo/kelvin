<?php

namespace App\Console\Commands;

use App\Services\SeoGenerator;
use Illuminate\Console\Command;

class GenerateSeoFiles extends Command
{
    protected $signature = 'seo:generate
                            {--robots   : Only regenerate robots.txt}
                            {--sitemap  : Only regenerate sitemap.xml}
                            {--llms     : Only regenerate llms.txt}';

    protected $description = 'Write static robots.txt, sitemap.xml, and llms.txt into public/';

    public function handle(): int
    {
        $targeted = $this->option('robots') || $this->option('sitemap') || $this->option('llms');

        if (! $targeted || $this->option('robots')) {
            SeoGenerator::generateRobots();
            $this->info('robots.txt written to public/');
        }

        if (! $targeted || $this->option('sitemap')) {
            SeoGenerator::generateSitemap();
            $this->info('sitemap.xml written to public/');
        }

        if (! $targeted || $this->option('llms')) {
            SeoGenerator::generateLlms();
            $this->info('llms.txt written to public/');
        }

        return self::SUCCESS;
    }
}
