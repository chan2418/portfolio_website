<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTypeFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_case_studies_type_filter_shows_only_matching_projects(): void
    {
        $websiteType = ProjectType::query()->create([
            'name' => 'Website',
            'slug' => 'website',
            'order_column' => 1,
            'is_active' => true,
        ]);

        $toolType = ProjectType::query()->create([
            'name' => 'Tool',
            'slug' => 'tool',
            'order_column' => 2,
            'is_active' => true,
        ]);

        Project::query()->create([
            'title' => 'Ecommerce Website',
            'slug' => 'ecommerce-website',
            'summary' => 'Website project.',
            'project_type_id' => $websiteType->id,
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        Project::query()->create([
            'title' => 'Inventory Tool',
            'slug' => 'inventory-tool',
            'summary' => 'Tool project.',
            'project_type_id' => $toolType->id,
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('case-studies.index', ['type' => 'website']))
            ->assertOk()
            ->assertSee('Ecommerce Website')
            ->assertDontSee('Inventory Tool');
    }
}
