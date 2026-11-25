<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Document;
use App\Models\Experience;
use App\Models\Gallery;
use App\Models\Lead;
use App\Models\Service;
use App\Support\RegulatoryServicesCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    public function home()
    {
        return $this->homeModern();
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
        try {
            $services = Service::all();
            $galleries = Gallery::orderBy('created_at', 'desc')->get();
            $experiences = Experience::orderBy('year', 'desc')->take(6)->get();
            $publicClients = \App\Models\Client::where('is_public', true)->get();
            $products = \App\Models\Product::active()->orderBy('order')->get();
        } catch (\Throwable $e) {
            $services = collect();
            $galleries = collect();
            $experiences = collect();
            $publicClients = collect();
            $products = collect();
        }

        $regulatoryServices = RegulatoryServicesCatalog::all();
        $positioning = RegulatoryServicesCatalog::positioning();
        $caseStudy = RegulatoryServicesCatalog::caseStudy();

        return view('home', array_merge(
            compact('services', 'galleries', 'experiences', 'publicClients', 'products', 'regulatoryServices', 'positioning', 'caseStudy'),
            [
                'meta_title' => 'Pharmaceutical Regulatory Affairs & Compliance Services in Tanzania',
                'meta_description' => 'iGEO Pharmacy & Consulting is a specialist pharmaceutical regulatory affairs, TMDA product registration, pharmacovigilance, market-entry and GSDP/GMP compliance consultancy in Tanzania.',
                'meta_keywords' => 'TMDA registration, pharmaceutical regulatory affairs Tanzania, pharmacovigilance Tanzania, Pharmacy Council, GSDP GDP audit, market entry Tanzania, iGEO Pharmacy & Consulting',
            ]
        ));
    }

    public function services()
    {
        $regulatoryServices = RegulatoryServicesCatalog::all();
        $positioning = RegulatoryServicesCatalog::positioning();
        $caseStudy = RegulatoryServicesCatalog::caseStudy();

        return view('public.services', [
            'regulatoryServices' => $regulatoryServices,
            'positioning' => $positioning,
            'caseStudy' => $caseStudy,
            'meta_title' => 'Regulatory Affairs & Pharmaceutical Compliance Services',
            'meta_description' => 'Explore iGEO Pharmacy & Consulting\'s eight core regulatory service lines: TMDA product registration, 24/7 pharmacovigilance, regulatory representation, import compliance, GSDP/GDP, GMP/QMS, clinical trials and market access advisory.',
            'meta_keywords' => 'TMDA product registration, pharmacovigilance PSMF Tanzania, local regulatory representation MAH, import permit TMDA, GSDP GDP warehouse audit, clinical trial NatHREC COSTECH',
        ]);
    }

    public function serviceDetail(string $slug)
    {
        $service = RegulatoryServicesCatalog::findBySlugOrId($slug);

        if (!$service) {
            abort(404);
        }

        $allServices = RegulatoryServicesCatalog::all();
        $positioning = RegulatoryServicesCatalog::positioning();
        $caseStudy = RegulatoryServicesCatalog::caseStudy();

        return view('public.service-detail', [
            'service' => $service,
            'allServices' => $allServices,
            'positioning' => $positioning,
            'caseStudy' => $caseStudy,
            'meta_title' => $service['name'] . ' | Regulatory Services',
            'meta_description' => $service['description'],
            'meta_keywords' => $service['name'] . ', TMDA, Tanzania regulatory affairs, iGEO Pharmacy & Consulting',
        ]);
    }

    public function submitConsultation(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_slug' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'product_category' => 'nullable|string|max:120',
            'message' => 'required|string|max:3000',
        ]);

        $structuredSummary = "[Service Requested: {$validated['service_name']}]"
            . (!empty($validated['product_category']) ? " [Category: {$validated['product_category']}]" : '')
            . "\n" . $validated['message'];

        try {
            Lead::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'message' => $structuredSummary,
                'status' => 'new',
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
            ]);
        } catch (\Throwable $e) {
            // Continue even if leads table differs in custom environment
        }

        try {
            DB::table('contact_submissions')->insert([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'project_type' => 'Consultation: ' . $validated['service_name'],
                'message' => (!empty($validated['company']) ? "Organization: {$validated['company']}\n" : '')
                    . (!empty($validated['product_category']) ? "Product/Scope Category: {$validated['product_category']}\n" : '')
                    . $validated['message'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
        }

        $redirectTarget = !empty($validated['service_slug'])
            ? route('public.service.detail', ['slug' => $validated['service_slug']]) . '#consultation'
            : url()->previous();

        return redirect($redirectTarget)->with(
            'consultation_success',
            'Thank you, ' . $validated['name'] . '! Your consultation request for "' . $validated['service_name'] . '" has been received. Our Regulatory Affairs team will contact you promptly.'
        );
    }

    public function about()
    {
        $aboutUs = \App\Models\AboutUs::active()->get();

        return view('public.about', array_merge(
            compact('aboutUs'),
            [
                'meta_title' => 'About Us',
                'meta_description' => 'Learn about iGEO Pharmacy & Consulting - our mission, vision, and values.',
                'meta_keywords' => 'about iGEO, mission, vision, pharmaceutical, regulatory consulting, Tanzania',
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
            $query->where(function ($q) use ($search) {
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
                'meta_title' => 'Blog & Regulatory Insights',
                'meta_description' => 'Read our latest articles and regulatory insights on pharmaceutical compliance and healthcare in Tanzania.',
                'meta_keywords' => 'blog, articles, pharmaceutical, TMDA, regulatory updates, health, insights',
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
                'meta_keywords' => $blog->tags ?? 'pharmaceutical, regulatory compliance',
                'og_image' => $blog->image ?? asset('Logo.png?v=20261125'),
                'og_type' => 'article',
            ]
        ));
    }

    public function documentation(Request $request)
    {
        $query = Document::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
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
                'meta_description' => 'Comprehensive documentation and regulatory guides for iGEO services.',
                'meta_keywords' => 'documentation, guides, technical, regulatory resources, iGEO, help',
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
                'meta_description' => 'Trusted by leading pharmaceutical and healthcare organizations.',
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
                'meta_description' => 'Join the iGEO Pharmacy & Consulting team! Explore career opportunities.',
                'meta_keywords' => 'careers, jobs, employment, pharmaceutical, regulatory affairs',
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
