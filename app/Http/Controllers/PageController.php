<?php

namespace App\Http\Controllers;

use App\Models\{Service, Post};
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(Request $request)
    {   
        // 1. Fetch active consulting services
        $consultingServices = Service::active()->consulting()->ordered()->get();
        // 2. Fetch active training classes
        $trainingClasses = Service::active()->training()->ordered()->get();
        return view('landing-pages.welcome',compact('consultingServices', 'trainingClasses'));
    }

    public function about()
    {
        return view('landing-pages.about');
    }

    /**
     * Display the Consulting Services listing.
     */
    public function services()
    {
        // 1. Fetch active consulting services
        $consultingServices = Service::active()->consulting()->ordered()->get();
        
        // 2. Fetch active training classes
        $trainingClasses = Service::active()->training()->ordered()->get();

        // 3. Pass BOTH variables to the view
        return view('landing-pages.services', compact('consultingServices', 'trainingClasses'));
    }

    /**
     * Display the Training Classes listing OR a specific Training Class.
     */
    public function training($slug = null)
    {
        // 1. If a slug is provided, show the single course detail page
        if (!is_null($slug)) {
            $service = Service::active()
                ->training()
                ->where('slug', $slug)
                ->firstOrFail(); // Will automatically throw a 404 if the slug is invalid

            return view('landing-pages.training-show', compact('service'));
        }
        
        // 2. If no slug is provided, show the main curriculum list
        $trainingClasses = Service::active()->training()->ordered()->get();

        return view('landing-pages.training', compact('trainingClasses'));
    }

    public function papers()
    {
        return view('landing-pages.papers');
    }


    public function blog(Request $request)
    {
        $search = $request->input('search');

        // Fetch published posts, eagerly load the category, apply search filter if present, and paginate.
        $posts = Post::with('category')
            ->published()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($qc) use ($search) {
                          $qc->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('published_at', 'desc')
            ->paginate(9); // Show 9 posts per page (3 rows)

        return view('landing-pages.blog', compact('posts', 'search'));
    }

    public function showBlog($slug)
    {
        // Fetch the post by slug, including its category and author.
        // firstOrFail() will automatically show a 404 page if the slug doesn't exist.
        $post = Post::with(['category', 'author'])
                    ->where('slug', $slug)
                    ->firstOrFail();

        return view('landing-pages.blog-show', compact('post'));
    }
}