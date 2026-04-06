<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_project_with_future_datetime_is_visible_on_public_pages(): void
    {
        $project = Project::query()->create([
            'title' => 'Future Date Project',
            'slug' => 'future-date-project',
            'summary' => 'Should still appear when status is published.',
            'status' => PublishStatus::Published->value,
            'is_featured' => true,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($project->title);

        $this->get(route('case-studies.index'))
            ->assertOk()
            ->assertSee($project->title);

        $this->get(route('case-studies.show', $project->slug))
            ->assertOk()
            ->assertSee($project->title);
    }
}
