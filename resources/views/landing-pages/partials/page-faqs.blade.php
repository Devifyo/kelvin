{{--
    Renders all ACTIVE FAQ sections for a given public page from the database,
    plus a single combined FAQPage JSON-LD for the URL.
    Params: $page (required), $ctaText / $ctaUrl (optional — shown after the last section)
--}}
{{-- Block form, not the @php(...) short form. Blade pairs @php with the next
     @endphp using a lazy regex, so a short-form @php earlier in the file would
     bind to the @endphp of the block below and swallow everything between. --}}
@php
    $__sections = \App\Models\FaqSection::forPage($page);
@endphp

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
    {{-- Build the array inside a PHP block, never inline in an unescaped echo.
         Blade treats the literal schema context key (at-sign + "context") as its
         own directive, which corrupts the JSON-LD and leaks compiled PHP into
         the page. Do not mention that key outside a PHP block — not even in a
         comment; Blade compiles it here too. --}}
    @php
        $_pageFaqJsonLd = json_encode([
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => collect(\App\Models\FaqSection::schemaItems($page))->map(fn ($f) => [
                '@type' => 'Question', 'name' => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(preg_replace('/\s+/', ' ', strip_tags($f['a'])))],
            ])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <script type="application/ld+json">{!! $_pageFaqJsonLd !!}</script>
    @endpush
@endif
