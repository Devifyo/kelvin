<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paper;
use App\Models\Category;
use Illuminate\Support\Str;

class PaperSeeder extends Seeder
{
    public function run()
    {
        // 1. Create the Categories (Type = 'paper')
        $catCaseStudies = Category::firstOrCreate(['slug' => 'case-studies'], ['name' => 'Case Studies', 'type' => 'paper']);
        $catWhitePapers = Category::firstOrCreate(['slug' => 'white-papers'], ['name' => 'White Papers', 'type' => 'paper']);
        $catPresentations = Category::firstOrCreate(['slug' => 'presentations'], ['name' => 'Presentations', 'type' => 'paper']);

        // 2. Create the Papers linked to the dynamic category IDs
        $papers = [
            [
                'title' => 'Eleven Lessons Learned about Agile Hardware Development',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Hardware Engagement',
                'description' => 'Thermo Fisher Scientific makes biotechnology equipment and supplies. This case study of an Agile transformation for the company shows lessons learned from Dr. Thompson’s first Agile hardware engagement.',
                'sort_order' => 1
            ],
            [
                'title' => 'Agile Processes for Hardware Development',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Methodology',
                'description' => 'This is the foundational publication on how to develop hardware products using an Agile process. It reflects 18 months of Dr. Thompson’s original research into the relevant issues.',
                'sort_order' => 2
            ],
            [
                'title' => 'The Agile Hardware Research Project',
                'category_id' => $catPresentations->id,
                'sub_category' => 'Hardware',
                'description' => 'This presentation lays out the findings from Dr. Thompson’s original research into the nature of Agile hardware development.',
                'sort_order' => 3
            ],
            // ... Add the rest of your papers here, assigning the correct $cat->id
        ];

        foreach ($papers as $paper) {
            Paper::create($paper);
        }
    }
}