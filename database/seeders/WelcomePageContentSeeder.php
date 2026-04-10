<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WelcomePageContent;

class WelcomePageContentSeeder extends Seeder
{
    public function run(): void
    {
        WelcomePageContent::updateOrCreate([
            'id' => 1
        ], [
            'hero_kicker' => 'Agile Hardware Consulting',
            'hero_h1_em' => 'Reduce risk.',
            'hero_h1_strong' => 'Ship faster.',
            'hero_p1' => 'We help hardware-development organizations reduce development risk and shorten time-to-market by applying Agile principles to prototype-driven learning, early system integration, and risk-focused development.',
            'hero_p2' => 'We understand how hardware development differs from software development, and how to apply Agile processes to the hardware world.',
            'hero_cta_primary_text' => 'Our Services',
            'hero_cta_primary_link' => route('services.training', [], false),
            'hero_cta_secondary_text' => 'Get in Touch',
            'hero_cta_secondary_link' => route('contact', [], false),
            
            'pain_title' => 'If your organization is experiencing...',
            'pain_list' => [
                'Discovery of critical problems too late during system integration.',
                'Development cycles that are extending significantly beyond projections.',
                'Late-stage design changes resulting in severe cost overruns.',
                'Hardware and software engineering teams operating at misaligned velocities.',
                'Extended design phases occurring prior to the validation of core assumptions.'
            ],
            'pain_footer' => '...we can help.',
            
            'principal_kicker' => 'Our Principal',
            'principal_h2_name' => 'Dr. Kevin',
            'principal_h2_em' => 'Thompson',
            'principal_p1' => 'Our Principal, Dr. Kevin Thompson, Ph.D. (Physics) is one of the most experienced Agile consultants in the field, having successfully completed more than 100 client engagements.',
            'principal_p2' => 'Dr. Thompson has helped numerous clients improve their ability to deliver both software and hardware products. He successfully pioneered Agile hardware development and remains a thought leader in the field. He has helped clients develop a variety of hardware products, from laboratory equipment to telecommunications products to jet engines.',
            'principal_p3' => 'He has written extensively on Agile topics, including in his book, <em>Solutions for Agile Governance in the Enterprise (Sage): Agile Project, Program, and Portfolio Management for Development of Hardware and Software Products.</em>',
            
            'principal_book_image' => 'https://m.media-amazon.com/images/I/61+CCARmhVL._SY522_.jpg',
            'principal_book_title' => 'Solutions for Agile Governance in the Enterprise (Sage)',
            'principal_book_desc' => 'Agile Project, Program, and Portfolio Management for Development of Hardware and Software Products.',
            'principal_book_url' => 'https://www.amazon.com/Solutions-Agile-Governance-Enterprise-SAGE/dp/0578420589',
            
            'seo_title' => 'Kevin Thompson Ph.D. Consulting | Agile Hardware & Software',
            'seo_description' => 'Expert consulting, training, and methodologies bridging the gap between hardware engineering and Agile software development.',
            'seo_keywords' => 'Agile Hardware, Scrum, Embedded Systems, Agile Consulting, Software Engineering',
        ]);
    }
}
