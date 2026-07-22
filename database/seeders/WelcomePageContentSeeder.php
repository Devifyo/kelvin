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
                'Don\'t discover the real problems until integration',
                'Have development cycles that are too long',
                'Discover too many risks only when they blow up—late',
                'Have late design changes that are extremely expensive',
                'Have hardware and software teams moving at different speeds',
                'Don\'t get real customer feedback until it\'s too late',
                'Have engineers spend months designing before testing assumptions',
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

            // Homepage FAQ — seeded here so it is editable in /admin/welcome-page
            // (the FAQ tab) and shown on the homepage by default.
            'faq_enabled'  => true,
            'faq_kicker'   => 'Common Questions',
            'faq_title'    => 'Frequently Asked',
            'faq_title_em' => 'Questions',
            'faq_items'    => [
                ['q' => 'Does Agile really work for hardware, not just software?', 'a' => 'Yes — but it must be adapted, not copied from software. Hardware has longer lead times, physical iteration costs, and integration risk, so sprints deliver progress (CAD, board routing, design reviews, parts on order) rather than shippable features. Dr. Thompson pioneered Agile for hardware and authored the foundational paper on it; this is the core of every engagement.'],
                ['q' => 'Will I work with Dr. Thompson directly, or a junior consultant?', 'a' => 'You work directly with Dr. Kevin Thompson, Ph.D. This is a senior, hands-on practice — not a staffing firm that sells you an expert and delivers a junior.'],
                ['q' => 'Have you worked in regulated environments like medical devices or FDA design controls?', 'a' => 'Yes. Agile and regulatory compliance are not mutually exclusive — the FDA and IEC 62304 are methodology-neutral and care that design controls are documented, not that you use waterfall. Dr. Thompson has written specifically on Agile development for FDA-regulated medical products and how iterative work actually improves traceability and risk discovery.'],
                ['q' => 'How long does an engagement take, and how is it priced?', 'a' => 'It depends on your goal. Engagements range from short assessments and targeted training through multi-month transformation and coaching programs, scoped to your organization’s size and complexity. Reach out with your situation and you’ll get a tailored proposal.'],
                ['q' => 'Do you work on-site, remote, or hybrid?', 'a' => 'Both. Engagements are delivered on-site at your facilities and can include remote coaching for distributed hardware and software teams across locations and time zones.'],
                ['q' => 'What results do clients typically see?', 'a' => 'Common outcomes are earlier discovery of design and integration problems, shorter and more predictable development cycles, and tighter alignment between hardware and software teams — fewer expensive late-stage surprises. Our Thermo Fisher Scientific case study walks through a real Agile hardware transformation.'],
                ['q' => 'How is Scrum different for hardware versus software?', 'a' => 'The framework is the same; the mechanics differ. Hardware sprints are often roughly twice the length of software sprints and must be sequenced around procurement lead time, the Product Owner is frequently a team member, and a sprint’s output is demonstrable progress rather than a releasable feature. We tailor each of these to your product.'],
                ['q' => 'Does the change stick after you leave?', 'a' => 'That’s the point. Engagements pair assessment and training with hands-on coaching and executive alignment so your teams own the process — the goal is durable capability, not dependence on a consultant.'],
            ],

            // "What We Offer" section — editable in /admin/welcome-page (Services tab).
            'offer_kicker'   => 'What We Offer',
            'offer_title'    => 'Consulting &',
            'offer_title_em' => 'Training Services',
            'offer_body'     => 'We offer a variety of consulting and training services. We can work with all levels at a client, from the hands-on engineers to the C-suite. We take the time to understand the unique needs of each client, and tailor consulting services accordingly.',
        ]);
    }
}
