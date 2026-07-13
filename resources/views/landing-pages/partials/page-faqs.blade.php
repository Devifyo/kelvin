{{--
    Renders all ACTIVE FAQ sections for a given public page from the database,
    plus a single combined FAQPage JSON-LD for the URL.
    Params: $page (required), $ctaText / $ctaUrl (optional — shown after the last section)
--}}
@php($__sections = \App\Models\FaqSection::forPage($page))

@if($__sections->isNotEmpty())
    @foreach($__sections as $__s)
        @include('landing-pages.partials.faq-block', [
            'kicker'  => $__s->kicker,
            'title'   => $__s->title,
            'titleEm' => $__s->title_em,
            'items'   => $__s->activeFaqs->map(fn ($f) => ['q' => $f->question, 'a' => $f->answer])->all(),
            'schema'  => false,
            'ctaText' => $loop->last ? ($ctaText ?? null) : null,
            'ctaUrl'  => $loop->last ? ($ctaUrl ?? null) : null,
        ])
    @endforeach

    @push('scripts')
    <script type="application/ld+json">{!! json_encode([
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => collect(\App\Models\FaqSection::schemaItems($page))->map(fn ($f) => [
            '@type' => 'Question', 'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(preg_replace('/\s+/', ' ', strip_tags($f['a'])))],
        ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endpush
@endif
