<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeProjectTypeCardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_horizontal_project_type_cards(): void
    {
        $websiteType = ProjectType::query()->create([
            'name' => 'Website',
            'slug' => 'website',
            'order_column' => 1,
            'is_active' => true,
        ]);

        Project::query()->create([
            'title' => 'Ecommerce Build',
            'slug' => 'ecommerce-build',
            'summary' => 'Ecommerce implementation.',
            'project_type_id' => $websiteType->id,
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Browse by project type')
            ->assertSee('Browse everything')
            ->assertSee('Website');
    }

    public function test_home_no_longer_renders_project_title_cards_in_projects_section(): void
    {
        $websiteType = ProjectType::query()->create([
            'name' => 'Website',
            'slug' => 'website',
            'order_column' => 1,
            'is_active' => true,
        ]);

        Project::query()->create([
            'title' => 'Title Should Not Render Here',
            'slug' => 'title-should-not-render-here',
            'summary' => 'Project summary.',
            'project_type_id' => $websiteType->id,
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Title Should Not Render Here');
    }
}
