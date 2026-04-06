<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\Project;
use App\Support\SeoManager;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function index(SeoManager $seoManager): View
    {
        $projects = Project::published()->latest('published_at')->paginate(9);

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'case-studies', [
            'title' => 'Case Studies',
            'description' => 'See measurable project outcomes, architecture decisions, and business impact from shipped products.',
        ]);

        return view('pages.case-studies.index', compact('projects', 'seo'));
    }

    public function show(string $slug, SeoManager $seoManager): View|Response
    {
        $project = Project::published()->where('slug', $slug)->first();

        if (! $project) {
            abort(404);
        }

        $seo = $seoManager->resolve(SeoPageType::Project->value, $project->slug, [
            'title' => $project->title,
            'description' => $project->summary,
            'og_image' => $project->cover_image_url,
        ]);

        return view('pages.case-studies.show', compact('project', 'seo'));
    }
}
