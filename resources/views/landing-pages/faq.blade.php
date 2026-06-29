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

@include('landing-pages.partials.page-faqs', [
    'page'    => 'faq',
    'ctaText' => 'Get in touch',
    'ctaUrl'  => route('contact'),
])

@endsection
