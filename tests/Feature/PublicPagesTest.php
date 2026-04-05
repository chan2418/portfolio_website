<?php

namespace Tests\Feature;

use Database\Seeders\DemoContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_available(): void
    {
        $this->seed(DemoContentSeeder::class);

        $project = \App\Models\Project::query()->firstOrFail();
        $post = \App\Models\BlogPost::query()->firstOrFail();

        $urls = [
            route('home'),
            route('services.index'),
            route('case-studies.index'),
            route('case-studies.show', $project->slug),
            route('blog.index'),
            route('blog.show', $post->slug),
            route('about'),
            route('contact.show'),
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }
}
