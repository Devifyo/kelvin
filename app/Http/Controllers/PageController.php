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

    public function training()
    {
        // Later, you can fetch dynamic training courses here
        // $courses = TrainingCourse::all();
        // return view('landing-pages.training', compact('courses'));
        
        return view('landing-pages.training');
    }

    public function papers()
    {
        // Later, you can fetch dynamic papers here
        // $caseStudies = Paper::where('category', 'Case Study')->get();
        // return view('landing-pages.papers', compact('caseStudies', ...));

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