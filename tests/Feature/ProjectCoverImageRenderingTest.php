<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCoverImageRenderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_external_cover_image_url_for_featured_project(): void
    {
        Project::query()->create([
            'title' => 'External Image Project',
            'slug' => 'external-image-project',
            'summary' => 'External image test project.',
            'status' => PublishStatus::Published->value,
            'is_featured' => true,
            'published_at' => now()->subMinute(),
            'cover_image' => 'https://example.com/project-cover.jpg',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('https://example.com/project-cover.jpg', escape: false);
    }

    public function test_case_study_pages_render_local_uploaded_cover_image_path(): void
    {
        $project = Project::query()->create([
            'title' => 'Local Image Project',
            'slug' => 'local-image-project',
            'summary' => 'Local image test project.',
            'status' => PublishStatus::Published->value,
            'is_featured' => true,
            'published_at' => now()->subMinute(),
            'cover_image' => 'projects/covers/local-image.jpg',
        ]);

        $this->get(route('case-studies.index'))
            ->assertOk()
            ->assertSee('/storage/projects/covers/local-image.jpg', escape: false);

        $this->get(route('case-studies.show', $project->slug))
            ->assertOk()
            ->assertSee('/storage/projects/covers/local-image.jpg', escape: false);
    }
}
