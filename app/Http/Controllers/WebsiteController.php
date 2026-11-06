<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\Gallery;
use App\Models\Experience;
use App\Models\Blog;
use App\Models\Document;

class WebsiteController extends Controller
{
    public function home()
    {
        // Get data for all sections of the homepage
        $services = Service::take(6)->get();
        $galleryImages = Gallery::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('year', 'desc')->take(3)->get();
        $latestBlogs = Blog::orderBy('created_at', 'desc')->take(3)->get();

        // Also get featured galleries for background slider
        $galleries = Gallery::where('featured', true)->take(5)->get();
        if ($galleries->isEmpty()) {
            $galleries = Gallery::take(5)->get();
        }
        
        // Get public clients
        $publicClients = \App\Models\Client::where('is_public', true)->get();

        return view('home', compact('services', 'galleryImages', 'galleries', 'experiences', 'latestBlogs', 'publicClients'));
    }

    public function homeNew()
    {
        $services = Service::take(6)->get();
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('year', 'desc')->take(6)->get();
        $publicClients = \App\Models\Client::where('is_public', true)->get();

        return view('home-new', compact('services', 'galleries', 'experiences', 'publicClients'));
    }

    public function homeRadical()
    {
        $services = Service::take(6)->get();
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('year', 'desc')->take(6)->get();
        $publicClients = \App\Models\Client::where('is_public', true)->get();

        return view('home-radical', compact('services', 'galleries', 'experiences', 'publicClients'));
    }

    public function homeAsymmetrical()
    {
        $services = Service::take(6)->get();
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('year', 'desc')->take(6)->get();
        $publicClients = \App\Models\Client::where('is_public', true)->get();

        return view('home-asymmetrical', compact('services', 'galleries', 'experiences', 'publicClients'));
    }

    public function homeModern()
    {
        $services = Service::all();
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('year', 'desc')->take(6)->get();
        $publicClients = \App\Models\Client::where('is_public', true)->get();
        $products = \App\Models\Product::active()->orderBy('order')->get();

        return view('home', array_merge(
            compact('services', 'galleries', 'experiences', 'publicClients', 'products'),
            [
                'meta_title' => 'Home',
                'meta_description' => 'iGEO Pharmacy & Consulting - Leading pharmaceutical and agribusiness solutions provider in Tanzania.',
                'meta_keywords' => 'pharmacy, consulting, agribusiness, healthcare, Tanzania, solutions',
            ]
        ));
    }

    public function about()
    {
        $aboutUs = \App\Models\AboutUs::active()->get();
        
        return view('public.about', array_merge(
            compact('aboutUs'),
            [
                'meta_title' => 'About Us',
                'meta_description' => 'Learn about iGEO Pharmacy & Consulting - our mission, vision, and values.',
                'meta_keywords' => 'about iGEO, mission, vision, pharmaceutical, agribusiness, Tanzania',
            ]
        ));
    }

    public function team()
    {
        $teamMembers = \App\Models\TeamMember::active()->ordered()->get();
        
        return view('public.team', array_merge(
            compact('teamMembers'),
            [
                'meta_title' => 'Our Team',
                'meta_description' => 'Meet the expert team at iGEO Pharmacy & Consulting.',
                'meta_keywords' => 'team, experts, pharmaceutical professionals, iGEO, Tanzania',
            ]
        ));
    }

    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'project_type' => 'nullable|string',
            'message' => 'required|string|max:2000',
        ]);

        DB::table('contact_submissions')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'project_type' => $request->project_type,
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Thank you for your message! We will contact you soon.');
    }

    public function blogs(Request $request)
    {
        $query = Blog::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate(9);
        $recentBlogs = Blog::orderBy('created_at', 'desc')->take(5)->get();

        return view('public.blogs', array_merge(
            compact('blogs', 'recentBlogs'),
            [
                'meta_title' => 'Blog',
                'meta_description' => 'Read our latest articles and insights on pharmaceutical and agribusiness.',
                'meta_keywords' => 'blog, articles, pharmaceutical, agribusiness, health, insights',
            ]
        ));
    }

    public function blog($id)
    {
        $blog = Blog::findOrFail($id);
        $relatedBlogs = Blog::where('id', '!=', $blog->id)
                           ->orderBy('created_at', 'desc')
                           ->take(3)
                           ->get();
        $recentBlogs = Blog::orderBy('created_at', 'desc')->take(5)->get();

        return view('public.blog-detail', array_merge(
            compact('blog', 'relatedBlogs', 'recentBlogs'),
            [
                'meta_title' => $blog->title ?? 'Blog Post',
                'meta_description' => substr(strip_tags($blog->content ?? $blog->excerpt ?? ''), 0, 160),
                'meta_keywords' => $blog->tags ?? 'pharmaceutical, agribusiness',
                'og_image' => $blog->image ?? asset('Logo.png'),
                'og_type' => 'article',
            ]
        ));
    }

    public function documentation(Request $request)
    {
        $query = Document::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(12);
        $categories = Document::distinct('category')
                             ->whereNotNull('category')
                             ->pluck('category')
                             ->sort();

        return view('public.documentation', array_merge(
            compact('documents', 'categories'),
            [
                'meta_title' => 'Documentation',
                'meta_description' => 'Comprehensive documentation and guides for iGEO products and services.',
                'meta_keywords' => 'documentation, guides, technical, resources, iGEO, help',
            ]
        ));
    }

    public function clients()
    {
        $publicClients = \App\Models\Client::where('is_public', true)->get();
        
        return view('public.clients', array_merge(
            compact('publicClients'),
            [
                'meta_title' => 'Our Clients',
                'meta_description' => 'Trusted by leading organizations across Africa.',
                'meta_keywords' => 'clients, partners, portfolio, case studies, pharmaceutical',
            ]
        ));
    }

    public function careers()
    {
        $careers = \App\Models\Career::active()->latest()->get();
        
        return view('public.careers', array_merge(
            compact('careers'),
            [
                'meta_title' => 'Careers',
                'meta_description' => 'Join the iGEO team! Explore career opportunities.',
                'meta_keywords' => 'careers, jobs, employment, pharmaceutical, agribusiness',
            ]
        ));
    }

    public function careerDetail($id)
    {
        $career = \App\Models\Career::active()->findOrFail($id);
        
        return view('public.career-detail', array_merge(
            compact('career'),
            [
                'meta_title' => $career->title ?? 'Career Opportunity',
                'meta_description' => substr(strip_tags($career->description ?? ''), 0, 160),
                'meta_keywords' => 'career, job, opportunity, ' . ($career->department ?? 'iGEO'),
            ]
        ));
    }
}