<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Str;

class ConsultingSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Assessment',
                'slug' => 'agile-assessment-services',
                'meta_title' => 'Agile Assessment Services | Identify Product Delivery Issues',
                'meta_description' => 'Discover issues interfering with product delivery through stakeholder interviews and analysis. Ideal for clarifying confusing development roadblocks.',
                'type' => 'consulting',
                'short_description' => 'An investigation into issues interfering with product delivery.',
                'content' => "An assessment is an investigation into issues that are interfering with the client’s ability to function well and deliver products on a timely basis.\n\nIt involves stakeholder interviews, analysis, preparation of findings and recommendations, and delivery of a final report which highlights action items to address the issues discovered.\n\nAssessments are particularly useful when the issues affecting the client are unclear and subject to confusion and disagreement.",
                'sort_order' => 1,
            ],
            [
                'title' => 'Advisory Engagement',
                'slug' => 'agile-advisory-engagement-coaching',
                'meta_title' => 'Agile Advisory Engagement & Coaching',
                'meta_description' => 'Get hands-on coaching and Q&A sessions to improve your existing Agile processes. Perfect for teams looking to optimize their workflow.',
                'type' => 'consulting',
                'short_description' => 'Question-and-answer sessions and hands-on coaching.',
                'content' => "In an advisory engagement, we are available for question-and-answer sessions and hands-on coaching about various Agile practices.\n\nAdvisory engagements are useful when a client has an existing Agile process but is dissatisfied with how well the process is working and wants some help improving it.",
                'sort_order' => 2,
            ],
            [
                'title' => 'Agile Transformation',
                'slug' => 'agile-transformation-consulting',
                'meta_title' => 'Agile Transformation Consulting Services',
                'meta_description' => 'Convert your development organization to an Agile process. Complete transformation services including scoping, planning, training, and coaching.',
                'type' => 'consulting',
                'short_description' => 'Converting a development organization to an Agile process.',
                'content' => "An Agile transformation takes a client through the process of converting a development organization from its previous state to an Agile process.\n\nThe scope can be as small as a single team, or as large as multiple teams spanning the globe for a large enterprise.\n\nThe basic stages of a transformation include scoping, planning, training, kick-off, and coaching the organization until people have mastered the new world well enough to stand on their own.\n\nDepending on needs, an Assessment may also be included.",
                'sort_order' => 3,
            ],
        ];

        // REMOVED: Service::where('type', 'consulting')->delete();

        foreach ($services as $data) {
            $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
            $data['is_active'] = true;
            
            // PRO APPROACH: Find by slug. If it exists, update it. If not, create it.
            Service::updateOrCreate(
                ['slug' => $data['slug']], // The unique constraint to check against
                $data                      // The data to insert or update
            );
        }
    }
}