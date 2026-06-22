<?php

namespace App\Http\Controllers;

use App\Models\AboutPageContent;
use App\Models\PrivacyPageContent;
use App\Models\TermsPageContent;
use App\Models\WelcomePageContent;
use App\Services\FrontendContentService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(
        protected FrontendContentService $contentService
    ) {}

    public function home()
    {
        $welcomeContent = WelcomePageContent::first();

        return view('landing-pages.welcome', [
            'consultingServices' => $this->contentService->getConsultingServices(),
            'trainingClasses' => $this->contentService->getTrainingClasses(),
            'welcomeContent' => $welcomeContent,
            'featuredClients' => $this->contentService->getFeaturedClients(),
            'clientsCount' => $this->contentService->getActiveClientsCount(),
        ]);
    }

    public function clients(Request $request)
    {
        // The page filters instantly client-side; this server-side filter is a
        // no-JS / shareable-link fallback so ?search= still returns matches.
        $search = trim((string) $request->input('search', ''));
        $clients = $this->contentService->getActiveClients();

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $clients = $clients
                ->filter(fn ($client) => str_contains(mb_strtolower($client->name), $needle))
                ->values();
        }

        return view('landing-pages.clients', compact('clients', 'search'));
    }

    public function about()
    {
        $aboutContent = AboutPageContent::first();

        return view('landing-pages.about', compact('aboutContent'));
    }

    public function faq()
    {
        return view('landing-pages.faq');
    }

    public function services()
    {
        return view('landing-pages.services', [
            'consultingServices' => $this->contentService->getConsultingServices(),
            'trainingClasses' => $this->contentService->getTrainingClasses(),
        ]);
    }

    public function training(?string $slug = null)
    {
        if ($slug) {
            $service = $this->contentService->getTrainingClassBySlug($slug);

            return view('landing-pages.training-show', compact('service'));
        }

        $trainingClasses = $this->contentService->getTrainingClasses();

        return view('landing-pages.training', compact('trainingClasses'));
    }

    public function papers(Request $request)
    {
        $currentFilter = $request->query('category', 'all');
        $data = $this->contentService->getPapersData($currentFilter);

        return view('landing-pages.papers', array_merge($data, [
            'currentFilter' => $currentFilter,
        ]));
    }

    public function blog(Request $request)
    {
        $search = $request->input('search');
        $posts = $this->contentService->getBlogPosts($search);

        return view('landing-pages.blog', compact('posts', 'search'));
    }

    public function showBlog(string $slug)
    {
        $post = $this->contentService->getPostBySlug($slug);

        return view('landing-pages.blog-show', compact('post'));
    }

    public function podcastsWebinars(Request $request)
    {
        $currentFilter = $request->query('type', 'all');
        $mediaItems = $this->contentService->getPodcastsWebinars($currentFilter);

        return view('landing-pages.podcasts-webinars', compact('mediaItems', 'currentFilter'));
    }

    public function privacy()
    {
        $privacyContent = PrivacyPageContent::first();

        return view('landing-pages.privacy', compact('privacyContent'));
    }

    public function terms()
    {
        $termsContent = TermsPageContent::first();

        return view('landing-pages.terms', compact('termsContent'));
    }
}
