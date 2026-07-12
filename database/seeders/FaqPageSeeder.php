<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqSection;
use Illuminate\Database\Seeder;

/**
 * Seeds the standalone /faq page content (page = 'faq') into the FAQ tables so
 * it can be managed from the admin panel (Admin → FAQ Manager).
 *
 * The content is imported directly from the canonical source file
 * resources/views/landing-pages/partials/faq-data.php (generated from
 * "Frequently Asked Questions - v2.docx"), so the database always matches the
 * file exactly. Answers are stored as rich HTML.
 */
class FaqPageSeeder extends Seeder
{
    public function run(): void
    {
        $file = resource_path('views/landing-pages/partials/faq-data.php');

        if (! is_file($file)) {
            $this->command?->warn("FaqPageSeeder: source file not found at {$file}; skipping.");

            return;
        }

        /** @var array<int,array{id:string,title:string,questions:array<int,array{id:string,q:string,a:string}>}> $data */
        $data = require $file;

        // Remove the legacy marketing FAQ sections that the standalone page replaced.
        FaqSection::whereIn('key', ['faq-basics', 'faq-logistics'])->each(function ($s) {
            $s->faqs()->delete();
            $s->delete();
        });

        $sectionCount = 0;
        $itemCount = 0;

        foreach ($data as $i => $section) {
            $key = 'faq-'.$section['id'];

            $model = FaqSection::updateOrCreate(
                ['key' => $key],
                [
                    'page'       => 'faq',
                    'name'       => $section['title'],
                    'kicker'     => 'Frequently Asked Questions',
                    'title'      => $section['title'],
                    'title_em'   => null,
                    'is_active'  => true,
                    'sort_order' => $i + 1,
                ]
            );
            $sectionCount++;

            // Replace this section's questions so the DB mirrors the file exactly.
            $model->faqs()->delete();

            foreach ($section['questions'] as $j => $q) {
                Faq::create([
                    'faq_section_id' => $model->id,
                    'question'       => $q['q'],
                    'answer'         => $q['a'],   // rich HTML
                    'sort_order'     => $j + 1,
                    'is_active'      => true,
                ]);
                $itemCount++;
            }
        }

        FaqSection::clearCache();
        $this->command?->info("Seeded {$sectionCount} FAQ-page sections with {$itemCount} questions from faq-data.php.");
    }
}
