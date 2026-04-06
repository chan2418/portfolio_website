<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTypeFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_case_studies_type_filter_shows_only_matching_projects(): void
    {
        Project::query()->create([
            'title' => 'Ecommerce Website',
            'slug' => 'ecommerce-website',
            'summary' => 'Website project.',
            'industry' => 'Website',
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        Project::query()->create([
            'title' => 'Inventory Tool',
            'slug' => 'inventory-tool',
            'summary' => 'Tool project.',
            'industry' => 'Tool',
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('case-studies.index', ['type' => 'website']))
            ->assertOk()
            ->assertSee('Ecommerce Website')
            ->assertDontSee('Inventory Tool');
    }
}
