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
        // 1. Create/Get the Parent Categories
        $catCaseStudies = Category::firstOrCreate(
            ['slug' => 'case-studies'], 
            ['name' => 'Case Studies', 'type' => 'paper']
        );
        $catWhitePapers = Category::firstOrCreate(
            ['slug' => 'white-papers'], 
            ['name' => 'White Papers', 'type' => 'paper']
        );
        $catPresentations = Category::firstOrCreate(
            ['slug' => 'presentations'], 
            ['name' => 'Presentations', 'type' => 'paper']
        );

        $data = [
            // --- CASE STUDIES: HARDWARE ---
            [
                'title' => 'Eleven Lessons Learned about Agile Hardware Development',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Hardware-Oriented Engagements',
                'description' => '<p>Thermo Fisher Scientific makes biotechnology equipment and supplies. This case study of an Agile transformation for the company shows lessons learned from Dr. Thompson’s first Agile hardware engagement. The lessons aligned surprisingly well with the predictions of the white paper, Agile Processes for Hardware Development.</p>',
                'file_path' => 'papers/LessonsLearned-AgileHardware-v4.pdf',
                'sort_order' => 1
            ],
            [
                'title' => 'AgileVox Scrum for Hardware',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Hardware-Oriented Engagements',
                'description' => '<p>This case study of the Thermo Fisher transformation was written up by and appeared in AgileVox magazine, a Scrum Alliance publication. It overlaps with the above case study but provides more perspectives from the participants in the transformation.</p>',
                'file_path' => 'papers/AgileVox-AgileHardware.pdf',
                'sort_order' => 2
            ],
            [
                'title' => 'Plantronics Case Study',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Hardware-Oriented Engagements',
                'description' => '<p>Plantronics has a lengthy pedigree in making audio headsets of all kinds. The Plantronics case study highlights the issues and successes of an Agile hardware process applied to a Research and Development organization.</p>',
                'file_path' => 'papers/Plantronics-CaseStudy-v2.pdf',
                'sort_order' => 3
            ],
            [
                'title' => 'Bird Technologies Case Study',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Hardware-Oriented Engagements',
                'description' => '<p>Bird Technologies makes radio-frequency communications products and test equipment. Dr. Thompson developed this case study because the Agile Alliance wanted him to deliver a presentation about the transformation work he did for Bird at an Agile Alliance conference. It highlights the challenges and successes of Bird’s Agile hardware transformation.</p>',
                'file_path' => 'papers/BirdTechnologies-CaseStudy-v2.pdf',
                'sort_order' => 4
            ],

            // --- CASE STUDIES: SOFTWARE ---
            [
                'title' => 'A Real Release-Planning Experience',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Software-Oriented Engagements',
                'description' => '<p>This case study of Accela shows the fine details of their first Release Planning experience. It makes a good reference for any team or company that is about to have their first such experience.</p>',
                'file_path' => 'papers/AccelaReleasePlanning-CaseStudy-v2.pdf',
                'sort_order' => 5
            ],
            [
                'title' => 'Agilent Case Study',
                'category_id' => $catCaseStudies->id,
                'sub_category' => 'Software-Oriented Engagements',
                'description' => '<p>This was a large Agile transformation, involving 14 teams spread across three continents. The case study documents the before and after states and describes basic elements of the transformation.</p>',
                'file_path' => 'papers/Agilent-CaseStudy-v2.pdf',
                'sort_order' => 6
            ],

            // --- WHITE PAPERS ---
            [
                'title' => 'Agile Processes for Hardware Development',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Methodology',
                'description' => '<p>This is the foundational publication on how to develop hardware products using an Agile process. It reflects 18 months of Dr. Thompson’s original research into the relevant issues and has been the basis for our subsequent work in the field.</p>',
                'file_path' => 'papers/AgileProcessesForHardwareDevelopment-v11.pdf',
                'sort_order' => 7
            ],
            [
                'title' => 'Agile Development for Medical Products',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Regulation',
                'description' => '<p>The development of medical products is regulated by the US Food and Drug Administration, whose regulations permeate all aspects of development. In spite of some beliefs to the contrary, FDA rules and Agile development can coexist without any difficulty. This paper addresses common issues with FDA regulation, especially around requirements management and traceability of test cases.</p>',
                'file_path' => 'papers/AgileDevelopmentForMedicalProducts-v4.pdf',
                'sort_order' => 8
            ],
            [
                'title' => 'The Price of Uncertainty',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Analysis',
                'description' => '<p>This paper conducts an analysis of two projects, both intended to produce the same Business Intelligence and Reporting system. One uses the classic Waterfall method, while the other uses an Agile process. The two projects are stress-tested with unforeseen challenges and delays, and the results are analyzed to show the differences in outcomes for the two projects. The Agile project emerges as the clear winner when uncertainty is high.</p>',
                'file_path' => 'papers/ThePriceofUncertainty-v3.pdf',
                'sort_order' => 9
            ],
            [
                'title' => 'The Agile PMO',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Management',
                'description' => '<p>Project, Program, and Project-Portfolio Management Organizations need to evolve from their classic roots in order to accommodate Agile processes. This paper analyzes the impacts of Agile processes on PMOs, PgMOs, and PPMOs. It reveals a surprising discovery, namely that the impact decreases as one moves from the PMO, through the PgMO, to the PPMO, because the PPMO is found to have a substantial “Agile flavor” from the beginning.</p>',
                'file_path' => 'papers/TheAgilePMO-v2.pdf',
                'sort_order' => 10
            ],
            [
                'title' => 'Recipes for Agile Governance in the Enterprise (RAGE)',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Governance',
                'description' => '<p>The purpose of this lengthy white paper was to develop scaling concepts for the Agile world. It pioneered Agile versions of Program and Portfolio management. The basis for the entire paper is a definition for governance that Dr. Thompson devised for it, namely, “Governance is the formalization and exercise of repeatable decision-making practices.” Much of the content for this paper was folded into the Sage book.</p>',
                'file_path' => 'papers/RecipesForAgileGovernanceInTheEnterprise-v2.pdf',
                'sort_order' => 11
            ],
            [
                'title' => 'Agile, Scrum, and “Hitting the Date”',
                'category_id' => $catWhitePapers->id,
                'sub_category' => 'Methodology',
                'description' => '<p>This paper examines some misconceptions around the inability to hit dates in product development with Scrum and clarifies that Scrum is actually a very date-oriented process.</p>',
                'file_path' => 'papers/HittingDates-v2.pdf',
                'sort_order' => 12
            ],

            // --- PRESENTATIONS ---
            [
                'title' => 'The Agile Hardware Research Project',
                'category_id' => $catPresentations->id,
                'sub_category' => 'Hardware-Oriented Presentations',
                'description' => '<p>This presentation lays out the findings from Dr. Thompson’s original research into the nature of Agile hardware development. It provided the foundation for all subsequent work he has done in this area.</p>',
                'file_path' => 'papers/AgileHardwareResearchProject-v2.pdf',
                'sort_order' => 13
            ],
            [
                'title' => 'Agile for Hardware: A Briefing to Gartner, Inc.',
                'category_id' => $catPresentations->id,
                'sub_category' => 'Hardware-Oriented Presentations',
                'description' => '<p>Gartner is a highly respected research and advisory company focusing on business and technology topics. This briefing that Dr. Thompson delivered to Gartner expresses the basic drivers and challenges of implementing hardware products with Agile processes.</p>',
                'file_path' => 'papers/GartnerAgileHardwareBriefing-v8.pdf',
                'sort_order' => 14
            ],
            [
                'title' => 'Scrum for Hardware Conference Keynote',
                'category_id' => $catPresentations->id,
                'sub_category' => 'Hardware-Oriented Presentations',
                'description' => '<p>The Scrum4HW conference was held under the auspices of the Scrum Alliance, to explore the possibility of using Scrum for hardware development. Dr. Thompson was contacted and asked to deliver a keynote presentation due to the article in AgileVox magazine about his introduction of Scrum for hardware development at Thermo Fisher Scientific. This presentation is the one that he delivered to the conference.</p>',
                'file_path' => 'papers/Scrum4HwConferenceKeynote-v3.pdf',
                'sort_order' => 15
            ],
        ];

        foreach ($data as $item) {
            Paper::updateOrCreate(
                ['title' => $item['title']], 
                $item
            );
        }
    }
}