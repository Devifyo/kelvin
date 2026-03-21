<?php

namespace App\Http\Controllers;

use App\Services\FrontendContentService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(
        protected FrontendContentService $contentService
    ) {}

    public function home()
    {   
        return view('landing-pages.welcome', [
            'consultingServices' => $this->contentService->getConsultingServices(),
            'trainingClasses'    => $this->contentService->getTrainingClasses(),
        ]);
    }

    public function about()
    {
        return view('landing-pages.about');
    }

    public function services()
    {
        return view('landing-pages.services', [
            'consultingServices' => $this->contentService->getConsultingServices(),
            'trainingClasses'    => $this->contentService->getTrainingClasses(),
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
            'currentFilter' => $currentFilter
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
}