<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home(Request $request)
    {
        return view('landing-pages.welcome');
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


    public function blog()
    {
        // Later, you can fetch dynamic blog posts here:
        // $posts = Post::where('is_published', true)->latest()->get();
        // return view('landing-pages.blog', compact('posts'));
        
        return view('landing-pages.blog');
    }

    public function showBlog($slug = 'embedded-software')
    {
        // Later, you will fetch the post from the database using the slug:
        // $post = Post::where('slug', $slug)->firstOrFail();
        // return view('landing-pages.blog-show', compact('post'));

        return view('landing-pages.blog-show');
    }
}