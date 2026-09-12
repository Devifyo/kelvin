# {{ $appName }}

> {!! $siteDesc !!}

## Pages

- [Home]({{ url('/') }}): Agile hardware development consulting and training by Dr. Kevin Thompson
- [About Kevin Thompson]({{ url('/about-kevin-thompson') }}): Biography, credentials, and expertise of Dr. Kevin Thompson Ph.D.
- [Agile Consulting Services]({{ url('/agile-consulting-services') }}): Hands-on agile coaching and consulting for hardware teams
- [Agile Training Classes]({{ url('/agile-training-classes') }}): Certified agile training courses for hardware engineers
- [Resource Library]({{ url('/agile-hardware-papers-and-presentations') }}): White papers, case studies, conference presentations, and books on agile hardware development — each resource has its own page with a summary and a link to the full document
- [Blog]({{ url('/agile-insights-blog') }}): Articles and insights on agile methodologies for hardware
- [Podcasts & Webinars]({{ url('/podcasts-webinars') }}): Recorded podcast appearances and webinar sessions
- [FAQ]({{ url('/faq') }}): Direct answers to common questions — can Scrum be used for hardware development, how hardware teams estimate work and manage dependencies, what Agile Release Planning is, and how hardware Product Owners work
- [Contact]({{ url('/contact-us') }}): Get in touch with Kevin Thompson

@if($trainings->isNotEmpty())
## Training Classes

@foreach($trainings as $training)
- [{{ $training->title }}]({{ url('/agile-training-classes/' . $training->slug) }}): {{ $training->short_description }}
@endforeach
@endif

@if($papers->isNotEmpty())
## Resource Library

@foreach($papers as $paper)
- [{{ $paper->title }}]({{ url('/agile-hardware-papers-and-presentations/' . $paper->slug) }}){{ $paper->category ? ' [' . $paper->category->name . ($paper->sub_category ? ' · ' . $paper->sub_category : '') . ']' : ($paper->sub_category ? ' [' . $paper->sub_category . ']' : '') }}{{ $paper->is_featured ? ' (featured)' : '' }}: {{ trim(preg_replace('/\s+/', ' ', strip_tags($paper->description))) }}
@endforeach
@endif

@if($posts->isNotEmpty())
## Blog Posts

@foreach($posts as $post)
- [{{ $post->title }}]({{ url('/agile-insights-blog/' . $post->slug) }}): {{ $post->excerpt }}
@endforeach
@endif
@if(trim($extra) !== '')

## Additional Information

{!! $extra !!}
@endif
