<?php

namespace App\Http\Controllers;

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

    public function services()
    {
        return view('landing-pages.services');
    }

    public function training($slug = null)
    {
        if(!is_null($slug)) {
            return view('landing-pages.training-show');
        }
        
        return view('landing-pages.training');
    }

    public function papers()
    {
        return view('landing-pages.papers');
    }

    public function contact()
    {
        return view('landing-pages.contact');
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