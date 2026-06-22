@extends('layouts.app')

@section('title', 'Frequently Asked Questions — Agile Hardware Consulting & Training | Kevin Thompson, Ph.D.')
@section('meta_description', 'Answers to common questions about Agile and Scrum for hardware development, FDA-regulated products, engagements, training, pricing, and working with Dr. Kevin Thompson, Ph.D.')
@section('meta_keywords', 'agile hardware faq, scrum for hardware, agile medical device, agile consulting questions, Kevin Thompson')
@section('og_type', 'website')

@push('styles')
<style>
.faq-page-header { background: var(--slate); padding: 11rem 4.5rem 6rem; text-align: center; position: relative; overflow: hidden; }
.faq-page-header::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 80% 80% at 50% 100%, rgba(47,66,89,.8) 0%, transparent 80%); }
.faq-page-header .inner { max-width: 820px; margin: 0 auto; position: relative; z-index: 1; }
.faq-page-header .kicker { display:inline-flex; align-items:center; gap:.75rem; font-family:-apple-system,sans-serif; font-size:.75rem; font-weight:700; letter-spacing:.2em; text-transform:uppercase; color:var(--copper); margin-bottom:1.25rem; }
.faq-page-header .kicker::before, .faq-page-header .kicker::after { content:''; width:30px; height:1px; background:var(--copper); }
.faq-page-header h1 { font-family:'Cormorant Garamond',serif; font-size:clamp(2.6rem,5vw,4rem); font-weight:400; color:#fff; line-height:1.1; margin-bottom:1.25rem; }
.faq-page-header h1 em { font-style:italic; color:var(--copper3); }
.faq-page-header p { font-family:-apple-system,sans-serif; font-size:1.05rem; color:rgba(250,247,242,.7); line-height:1.8; font-weight:300; max-width:620px; margin:0 auto; }
@media(max-width:768px){ .faq-page-header{ padding:9rem 1.75rem 4rem; } }
</style>
@endpush

@section('content')

<section class="faq-page-header">
    <div class="inner">
        <div class="kicker">Frequently Asked Questions</div>
        <h1>Agile Hardware, <em>Answered</em></h1>
        <p>Straight answers about applying Agile and Scrum to hardware development, working in regulated environments, how engagements run, and what it's like to work with Dr. Kevin Thompson, Ph.D.</p>
    </div>
</section>

@php
    $faqBasics = [
        ['q' => 'What is Agile hardware development?', 'a' => 'Agile hardware development applies iterative, feedback-driven principles — short cycles, early integration, and risk-focused prioritisation — to the design of physical products. Unlike software, hardware sprints deliver demonstrable progress (CAD models, board routing, design reviews, parts on order) rather than shippable features, and they must be sequenced around procurement lead time.'],
        ['q' => 'Does Agile/Scrum really work for hardware, or only software?', 'a' => 'It works, but it must be adapted rather than copied from software. The framework is the same; the mechanics differ because of lead time, physical iteration cost, and integration risk. Dr. Thompson pioneered Agile for hardware and authored the foundational paper on it.'],
        ['q' => 'How is Scrum for hardware different from Scrum for software?', 'a' => 'Hardware sprints are often roughly twice the length of software sprints and are sequenced to account for procurement lead time; the Product Owner is frequently a team member; and a sprint produces demonstrable progress rather than a releasable feature. Each of these is tailored to your product and organisation.'],
        ['q' => 'How long should a hardware sprint be?', 'a' => 'There is no universal number, but hardware sprints commonly run longer than the two-week software norm — frequently around twice as long — and are timed so planning cycles line up with parts lead times. The right cadence depends on your product and supply chain.'],
    ];
    $faqLogistics = [
        ['q' => 'Will I work with Dr. Thompson directly?', 'a' => 'Yes. This is a senior, hands-on practice — you work directly with Dr. Kevin Thompson, Ph.D., not a junior assigned after the sale.'],
        ['q' => 'Have you worked in regulated environments like FDA-controlled medical devices?', 'a' => 'Yes. Agile and regulatory compliance are not mutually exclusive — the FDA and IEC 62304 are methodology-neutral and require that design controls are documented, not that you use waterfall. Dr. Thompson has written specifically on Agile development for FDA-regulated medical products.'],
        ['q' => 'How long does an engagement take, and how is it priced?', 'a' => 'It is scoped to your goal — from a short assessment or training through multi-month transformation and coaching. Share your situation and you will receive a tailored proposal.'],
        ['q' => 'Do you work on-site, remotely, or hybrid?', 'a' => 'Both. Engagements are delivered on-site at your facilities and include remote coaching for distributed hardware and software teams across locations and time zones.'],
        ['q' => 'What results do clients typically see?', 'a' => 'Common outcomes are earlier discovery of design and integration problems, shorter and more predictable development cycles, and tighter alignment between hardware and software teams — fewer expensive late-stage surprises.'],
        ['q' => 'Does the change last after you leave?', 'a' => 'That is the objective. Coaching is paired with training and executive alignment so your teams own the process — the goal is durable capability, not dependence on a consultant.'],
    ];
@endphp

@include('landing-pages.partials.faq-block', ['kicker' => 'The Basics', 'title' => 'Agile for Hardware', 'titleEm' => 'Explained', 'schema' => false, 'items' => $faqBasics])

@include('landing-pages.partials.faq-block', ['kicker' => 'Working Together', 'title' => 'Engagements &', 'titleEm' => 'Logistics', 'ctaText' => 'Get in touch', 'ctaUrl' => route('contact'), 'schema' => false, 'items' => $faqLogistics])

@endsection

@push('scripts')
{{-- Single combined FAQPage for this URL --}}
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => collect(array_merge($faqBasics, $faqLogistics))->map(fn ($f) => [
        '@type' => 'Question', 'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush
