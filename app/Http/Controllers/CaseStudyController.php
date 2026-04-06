<?php

namespace App\Http\Controllers;

use App\Enums\SeoPageType;
use App\Models\Project;
use App\Models\ProjectType;
use App\Support\SeoManager;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function index(Request $request, SeoManager $seoManager): View
    {
        $projectsQuery = Project::published()
            ->with('projectType')
            ->latest('published_at');

        $availableTypes = ProjectType::active()
            ->withCount('publishedProjects')
            ->get()
            ->filter(fn (ProjectType $projectType): bool => $projectType->published_projects_count > 0)
            ->values();

        $selectedTypeSlug = Str::slug((string) $request->query('type'));
        $selectedType = null;

        if ($selectedTypeSlug !== '' && $selectedTypeSlug !== 'all') {
            $selectedType = $availableTypes->first(
                fn (ProjectType $projectType): bool => $projectType->slug === $selectedTypeSlug
            );

            if ($selectedType) {
                $projectsQuery->where('project_type_id', $selectedType->id);
            }
        }

        $projects = $projectsQuery->paginate(9)->withQueryString();

        $seo = $seoManager->resolve(SeoPageType::Static->value, 'case-studies', [
            'title' => 'Case Studies',
            'description' => 'See measurable project outcomes, architecture decisions, and business impact from shipped products.',
        ]);

        return view('pages.case-studies.index', compact('projects', 'seo', 'availableTypes', 'selectedType'));
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
