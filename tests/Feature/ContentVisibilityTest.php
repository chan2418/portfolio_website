<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_content_is_not_visible_on_public_routes(): void
    {
        $publishedProject = Project::query()->create([
            'title' => 'Published Project',
            'slug' => 'published-project',
            'summary' => 'Visible summary',
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        $draftProject = Project::query()->create([
            'title' => 'Draft Project',
            'slug' => 'draft-project',
            'summary' => 'Hidden summary',
            'status' => PublishStatus::Draft->value,
        ]);

        $publishedPost = BlogPost::query()->create([
            'title' => 'Published Post',
            'slug' => 'published-post',
            'content' => '<p>Visible post</p>',
            'status' => PublishStatus::Published->value,
            'published_at' => now()->subDay(),
        ]);

        $draftPost = BlogPost::query()->create([
            'title' => 'Draft Post',
            'slug' => 'draft-post',
            'content' => '<p>Hidden post</p>',
            'status' => PublishStatus::Draft->value,
        ]);

        $this->get(route('case-studies.index'))
            ->assertOk()
            ->assertSee($publishedProject->title)
            ->assertDontSee($draftProject->title);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee($publishedPost->title)
            ->assertDontSee($draftPost->title);

        $this->get(route('case-studies.show', $draftProject->slug))->assertNotFound();
        $this->get(route('blog.show', $draftPost->slug))->assertNotFound();
    }
}
