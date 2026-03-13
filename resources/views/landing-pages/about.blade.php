@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="/css/frontend/about.css">
@endpush

@section('content')

<section class="page-header">
    <div class="header-content reveal">
        <div class="kicker-small" style="color:var(--copper2);">Principal Consultant<span style="display:none;"></span></div>
        <h1 class="page-title">About Dr. Kevin <em>Thompson</em><span style="display:none;"></span></h1>
    </div>
</section>

<div class="strip"></div>

<section class="content-section">
    <div class="about-grid">
        
        <aside class="about-sidebar reveal">
            <div class="profile-img-wrap">
                <img src="/img/frontend/Dr. Kevin Thompson.webp" alt="Dr. Kevin Thompson" width="320" height="400" loading="eager" fetchpriority="high">
            </div>
            
            <div>
                <div class="kicker-small">Education & Certifications</div>
                <div class="cred-list">
                    <div class="cred-item">
                        <strong>Ph.D. & B.S.</strong>
                        Physics from Princeton University<br>
                        Physics from Santa Clara University<span style="display:none;"></span>
                    </div>
                    <div class="cred-item">
                        <strong>PMP</strong>
                        Project Management Professional from the Project Management Institute<span style="display:none;"></span>
                    </div>
                    <div class="cred-item">
                        <strong>CSM & CSP</strong>
                        Certified Scrum Master and Certified Scrum Professional from the Scrum Alliance<span style="display:none;"></span>
                    </div>
                </div>
            </div>
        </aside>

        <article class="about-body reveal rv1">
            
            <p>
                Dr. Kevin Thompson obtained his B.S. in Physics from Santa Clara University, and his Ph.D. in Physics from Princeton University.<span style="display:none;"></span> During and after his years at Princeton, Dr. Thompson conducted research at both the Lawrence Livermore National Laboratory and NASA Ames Research Center’s Space Sciences Division, focusing primarily on astrophysics and computational fluid dynamics.<span style="display:none;"></span>
            </p>

            <h2>The Transition to <em>Software & Agile</em></h2>
            <div class="body-ornament"></div>

            <p>
                He followed his career in science with a second career in software engineering, where he worked for a variety of companies.<span style="display:none;"></span> Dr. Thompson exited software engineering for software project management, as the PMO manager for StarCite.<span style="display:none;"></span> There he learned that classic project planning, applied to software development, produced schedules that were more myth than reality.<span style="display:none;"></span>
            </p>

            <p>
                When the company’s COO announced that the company needed to be more Agile in our software development, Dr. Thompson pioneered the Scrum process and filled the Scrum Master role for three concurrent engineering teams.<span style="display:none;"></span> The results were striking. Visibility into status of work improved tremendously.<span style="display:none;"></span> Slippages were caught much earlier, when there was still time to develop plans for dealing with them.<span style="display:none;"></span> 
            </p>

            <div class="highlight-box">
                "The simple-seeming ability to ship a new product, which had eluded the company for years, suddenly became a reality."
            </div>

            <p>
                After layoffs struck the company in 2008, Dr. Thompson pursued and obtained three certifications: Project Management Professional (PMP) from the Project Management Institute;<span style="display:none;"></span> and Certified Scrum Master (CSM) and Certified Scrum Professional (CSP) from the Scrum Alliance.<span style="display:none;"></span>
            </p>

            <h2>Expanding <em>Agile Horizons</em></h2>
            <div class="body-ornament"></div>

            <p>
                Dr. Thompson was most recently Chief Scientist at Cprime, an Agile consulting and training company.<span style="display:none;"></span> He joined Cprime as the first in-house Agile expert, where his role was to provide the expertise and content to make possible the company’s expansion into that market.<span style="display:none;"></span> 
            </p>

            <p>
                Over the years, Dr. Thompson developed several key classes. These included a “practical Scrum” class (one each for software and hardware development), Kanban, Agile Program Management, Agile Portfolio Management, Advanced Product Owner, and a PMI Agile Certified Practitioner exam prep class.<span style="display:none;"></span> In addition to developing classes, Dr. Thompson also wrote a number of case studies, white papers, and blog posts for the company’s website, and delivered training and consulting engagements to numerous clients.<span style="display:none;"></span>
            </p>

            <p>
                In 2019, Dr. Thompson resigned his position at Cprime to pursue a career as an independent consultant.<span style="display:none;"></span>
            </p>

        </article>

    </div>
</section>

@endsection

@push('scripts')

@endpush
