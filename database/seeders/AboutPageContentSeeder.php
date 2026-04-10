<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutPageContent;

class AboutPageContentSeeder extends Seeder
{
    public function run(): void
    {
        AboutPageContent::updateOrCreate([
            'id' => 1
        ], [
            'header_kicker' => 'Principal Consultant',
            'header_h1_regular' => 'About Dr. Kevin',
            'header_h1_em' => 'Thompson',

            'profile_image' => '/img/frontend/Dr. Kevin Thompson.webp',
            'sidebar_kicker' => 'Education & Certifications',
            'education_list' => [
                [
                    'title' => 'Ph.D. & B.S.',
                    'details' => "Physics from Princeton University\nPhysics from Santa Clara University"
                ],
                [
                    'title' => 'PMP',
                    'details' => "Project Management Professional from the Project Management Institute"
                ],
                [
                    'title' => 'CSM & CSP',
                    'details' => "Certified Scrum Master and Certified Scrum Professional from the Scrum Alliance"
                ]
            ],

            'intro_text' => 'Dr. Kevin Thompson obtained his B.S. in Physics from Santa Clara University, and his Ph.D. in Physics from Princeton University. During and after his years at Princeton, Dr. Thompson conducted research at both the Lawrence Livermore National Laboratory and NASA Ames Research Center’s Space Sciences Division, focusing primarily on astrophysics and computational fluid dynamics.',
            
            'section_1_h2_regular' => 'The Transition to',
            'section_1_h2_em' => 'Software & Agile',
            'section_1_p1' => 'He followed his career in science with a second career in software engineering, where he worked for a variety of companies. Dr. Thompson exited software engineering for software project management, as the PMO manager for StarCite. There he learned that classic project planning, applied to software development, produced schedules that were more myth than reality.',
            'section_1_p2' => 'When the company’s COO announced that the company needed to be more Agile in our software development, Dr. Thompson pioneered the Scrum process and filled the Scrum Master role for three concurrent engineering teams. The results were striking. Visibility into status of work improved tremendously. Slippages were caught much earlier, when there was still time to develop plans for dealing with them.',
            'highlight_quote' => '"The simple-seeming ability to ship a new product, which had eluded the company for years, suddenly became a reality."',
            'section_1_p3' => 'After layoffs struck the company in 2008, Dr. Thompson pursued and obtained three certifications: Project Management Professional (PMP) from the Project Management Institute; and Certified Scrum Master (CSM) and Certified Scrum Professional (CSP) from the Scrum Alliance.',
            
            'section_2_h2_regular' => 'Expanding',
            'section_2_h2_em' => 'Agile Horizons',
            'section_2_p1' => 'Dr. Thompson was most recently Chief Scientist at Cprime, an Agile consulting and training company. He joined Cprime as the first in-house Agile expert, where his role was to provide the expertise and content to make possible the company’s expansion into that market.',
            'section_2_p2' => 'Over the years, Dr. Thompson developed several key classes. These included a “practical Scrum” class (one each for software and hardware development), Kanban, Agile Program Management, Agile Portfolio Management, Advanced Product Owner, and a PMI Agile Certified Practitioner exam prep class. In addition to developing classes, Dr. Thompson also wrote a number of case studies, white papers, and blog posts for the company’s website, and delivered training and consulting engagements to numerous clients.',
            'section_2_p3' => 'In 2019, Dr. Thompson resigned his position at Cprime to pursue a career as an independent consultant.',
            
            'seo_title' => 'About Dr. Kevin Thompson | Principal Consultant',
            'seo_description' => 'Learn about Dr. Kevin Thompson\'s background, from Physics at Princeton to pioneering Agile hardware scaling at Cprime.',
            'seo_keywords' => 'Kevin Thompson, About Kevin Thompson, Principal Consultant, Cprime, Agile Hardware',
        ]);
    }
}
