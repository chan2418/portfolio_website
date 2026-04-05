<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\Service;
use App\Support\SeoManager;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(SeoManager $seoManager): View
    {
        $services = Service::published()->get();

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'services', [
            'title' => 'Services',
            'description' => 'Explore full-stack product design, Laravel engineering, and growth-focused web delivery services.',
        ]);

        return view('pages.services.index', compact('services', 'seo'));
    }
}
