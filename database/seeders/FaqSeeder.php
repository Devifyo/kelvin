<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqSection;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'services', 'page' => 'services', 'name' => 'Services Page FAQ',
                'kicker' => 'Consulting FAQ', 'title' => 'Engagement', 'title_em' => 'Questions', 'sort_order' => 1,
                'items' => [
                    ['What does an Agile assessment involve?', 'A focused review of how your hardware and software teams plan, prioritise, and integrate — interviews, process review, and a look at your delivery data — resulting in concrete, prioritised recommendations you can act on. It is the fastest way to find what is actually slowing your program down.'],
                    ['Who do you work with inside an organization?', 'Everyone from hands-on engineers to the C-suite. Durable change needs both team-level coaching and executive alignment, so engagements are tailored to reach the levels that matter for your specific goal.'],
                    ['How long does an engagement take, and how is it priced?', 'It is scoped to your goal — from a short assessment or training through multi-month transformation and coaching. Reach out with your situation and you will get a tailored proposal rather than a one-size package.'],
                    ['Do you deliver on-site or remotely?', 'Both. Engagements are delivered on-site at your facilities and include remote coaching for distributed hardware and software teams across locations and time zones.'],
                    ['Will the improvements last after the engagement ends?', 'That is the objective. Coaching is paired with training and executive alignment so your teams own the process — the goal is durable capability, not dependence on a consultant.'],
                ],
            ],
            [
                'key' => 'training', 'page' => 'training', 'name' => 'Training Page FAQ',
                'kicker' => 'Training FAQ', 'title' => 'Training', 'title_em' => 'Questions', 'sort_order' => 1,
                'items' => [
                    ['Are classes live or self-paced?', 'All classes are live and instructor-led, taught by Dr. Kevin Thompson personally — delivered on-site at your location or remotely for distributed teams.'],
                    ['Is the training specific to hardware, or generic Agile?', 'Both options exist. We teach Scrum and Kanban for software teams and, distinctively, Scrum applied to hardware and embedded development — covering lead time, integration, and the realities of physical product development that generic Agile training ignores.'],
                    ['Do your classes apply in regulated environments like FDA-controlled medical devices?', 'Yes. Agile and design controls coexist — the FDA and IEC 62304 are methodology-neutral. Dr. Thompson has written specifically on Agile for FDA-regulated products, and that perspective is built into the relevant classes.'],
                    ['How large should a class be?', 'Classes are tailored to your team. They work well for a single Scrum team up to multiple teams in a program; we will recommend the right format when scoping.'],
                    ['Can training be combined with coaching?', 'Yes — and it usually should be. Training builds shared understanding; follow-on coaching during your first sprints is what makes the change actually stick.'],
                ],
            ],
            [
                'key' => 'faq-basics', 'page' => 'faq', 'name' => 'FAQ Page — The Basics',
                'kicker' => 'The Basics', 'title' => 'Agile for Hardware', 'title_em' => 'Explained', 'sort_order' => 1,
                'items' => [
                    ['What is Agile hardware development?', 'Agile hardware development applies iterative, feedback-driven principles — short cycles, early integration, and risk-focused prioritisation — to the design of physical products. Unlike software, hardware sprints deliver demonstrable progress (CAD models, board routing, design reviews, parts on order) rather than shippable features, and they must be sequenced around procurement lead time.'],
                    ['Does Agile/Scrum really work for hardware, or only software?', 'It works, but it must be adapted rather than copied from software. The framework is the same; the mechanics differ because of lead time, physical iteration cost, and integration risk. Dr. Thompson pioneered Agile for hardware and authored the foundational paper on it.'],
                    ['How is Scrum for hardware different from Scrum for software?', 'Hardware sprints are often roughly twice the length of software sprints and are sequenced to account for procurement lead time; the Product Owner is frequently a team member; and a sprint produces demonstrable progress rather than a releasable feature. Each of these is tailored to your product and organisation.'],
                    ['How long should a hardware sprint be?', 'There is no universal number, but hardware sprints commonly run longer than the two-week software norm — frequently around twice as long — and are timed so planning cycles line up with parts lead times. The right cadence depends on your product and supply chain.'],
                ],
            ],
            [
                'key' => 'faq-logistics', 'page' => 'faq', 'name' => 'FAQ Page — Engagements & Logistics',
                'kicker' => 'Working Together', 'title' => 'Engagements &', 'title_em' => 'Logistics', 'sort_order' => 2,
                'items' => [
                    ['Will I work with Dr. Thompson directly?', 'Yes. This is a senior, hands-on practice — you work directly with Dr. Kevin Thompson, Ph.D., not a junior assigned after the sale.'],
                    ['Have you worked in regulated environments like FDA-controlled medical devices?', 'Yes. Agile and regulatory compliance are not mutually exclusive — the FDA and IEC 62304 are methodology-neutral and require that design controls are documented, not that you use waterfall. Dr. Thompson has written specifically on Agile development for FDA-regulated medical products.'],
                    ['How long does an engagement take, and how is it priced?', 'It is scoped to your goal — from a short assessment or training through multi-month transformation and coaching. Share your situation and you will receive a tailored proposal.'],
                    ['Do you work on-site, remotely, or hybrid?', 'Both. Engagements are delivered on-site at your facilities and include remote coaching for distributed hardware and software teams across locations and time zones.'],
                    ['What results do clients typically see?', 'Common outcomes are earlier discovery of design and integration problems, shorter and more predictable development cycles, and tighter alignment between hardware and software teams — fewer expensive late-stage surprises.'],
                    ['Does the change last after you leave?', 'That is the objective. Coaching is paired with training and executive alignment so your teams own the process — the goal is durable capability, not dependence on a consultant.'],
                ],
            ],
        ];

        foreach ($sections as $data) {
            $items = $data['items'];
            unset($data['items']);

            $section = FaqSection::updateOrCreate(['key' => $data['key']], $data);

            foreach ($items as $i => [$q, $a]) {
                Faq::updateOrCreate(
                    ['faq_section_id' => $section->id, 'question' => $q],
                    ['answer' => $a, 'sort_order' => $i + 1, 'is_active' => true]
                );
            }
        }

        // The homepage FAQ is managed via WelcomePageContent.faq_items, not the
        // FaqSection system. Drop any legacy 'home' section so it doesn't appear
        // as an orphan in the FAQ manager.
        FaqSection::where('page', 'home')->each(function ($s) {
            $s->faqs()->delete();
            $s->delete();
        });

        FaqSection::clearCache();
        $this->command?->info('Seeded '.count($sections).' FAQ sections.');
    }
}
