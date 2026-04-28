# {{ $appName }}

> {!! $siteDesc !!}

## Pages

- [Home]({{ url('/') }}): Agile hardware development consulting and training by Dr. Kevin Thompson
- [About Kevin Thompson]({{ url('/about-kevin-thompson') }}): Biography, credentials, and expertise of Dr. Kevin Thompson Ph.D.
- [Agile Consulting Services]({{ url('/agile-consulting-services') }}): Hands-on agile coaching and consulting for hardware teams
- [Agile Training Classes]({{ url('/agile-training-classes') }}): Certified agile training courses for hardware engineers
- [Papers & Presentations]({{ url('/agile-hardware-papers-and-presentations') }}): Research papers and conference presentations on agile hardware development
- [Blog]({{ url('/agile-insights-blog') }}): Articles and insights on agile methodologies for hardware
- [Podcasts & Webinars]({{ url('/podcasts-webinars') }}): Recorded podcast appearances and webinar sessions
- [Contact]({{ url('/contact-us') }}): Get in touch with Kevin Thompson

@if($trainings->isNotEmpty())
## Training Classes

@foreach($trainings as $training)
- [{{ $training->title }}]({{ url('/agile-training-classes/' . $training->slug) }}): {{ $training->short_description }}
@endforeach
@endif

@if($papers->isNotEmpty())
## Papers & Presentations

@foreach($papers as $paper)
- [{{ $paper->title }}]({{ url('/agile-hardware-papers-and-presentations') }}){{ $paper->sub_category ? ' [' . $paper->sub_category . ']' : '' }}: {{ $paper->description }}
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
