<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Service;
use App\Models\Testimonial;
use App\Support\SeoManager;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(SeoManager $seoManager): View
    {
        $services = Service::published()->limit(3)->get();
        $totalPublishedProjects = Project::published()->count();
        $projectTypes = ProjectType::active()
            ->withCount('publishedProjects')
            ->get()
            ->filter(fn (ProjectType $projectType): bool => $projectType->published_projects_count > 0)
            ->values();

        $testimonials = Testimonial::active()->limit(4)->get();
        $latestPosts = BlogPost::published()->latest('published_at')->limit(3)->get();
        $faqs = Faq::active()->limit(5)->get();

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'home', [
            'title' => 'Personal Portfolio',
            'description' => 'Explore personal projects, services, and writing built with Laravel and product-focused execution.',
        ]);

        return view('pages.home', compact('services', 'projectTypes', 'totalPublishedProjects', 'testimonials', 'latestPosts', 'faqs', 'seo'));
    }
}
