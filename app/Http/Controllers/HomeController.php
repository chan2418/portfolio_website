<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use App\Support\SeoManager;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(SeoManager $seoManager): View
    {
        $services = Service::published()->limit(3)->get();

        $featuredProjects = Project::published()
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($featuredProjects->isEmpty()) {
            $featuredProjects = Project::published()->latest('published_at')->limit(3)->get();
        }

        $testimonials = Testimonial::active()->limit(4)->get();
        $latestPosts = BlogPost::published()->latest('published_at')->limit(3)->get();
        $faqs = Faq::active()->limit(5)->get();

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'home', [
            'title' => 'Personal Portfolio',
            'description' => 'Explore personal projects, services, and writing built with Laravel and product-focused execution.',
        ]);

        return view('pages.home', compact('services', 'featuredProjects', 'testimonials', 'latestPosts', 'faqs', 'seo'));
    }
}
