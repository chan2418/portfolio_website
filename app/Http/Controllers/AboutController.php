<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\Testimonial;
use App\Support\SeoManager;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(SeoManager $seoManager): View
    {
        $testimonials = Testimonial::active()->limit(3)->get();

        $milestones = [
            ['year' => 'Plan', 'summary' => 'Understand goals, positioning, and project priorities.'],
            ['year' => 'Build', 'summary' => 'Ship scalable Laravel features with clean UX.'],
            ['year' => 'Improve', 'summary' => 'Use feedback and analytics to keep improving outcomes.'],
        ];

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'about', [
            'title' => 'About & Process',
            'description' => 'Background, process, and delivery style behind this personal brand portfolio.',
        ]);

        return view('pages.about', compact('testimonials', 'milestones', 'seo'));
    }
}
