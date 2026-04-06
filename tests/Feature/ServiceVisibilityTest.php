<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_services_show_even_when_published_at_is_in_future(): void
    {
        Service::query()->create([
            'title' => 'Automation Engineering',
            'slug' => 'automation-engineering',
            'excerpt' => 'Pipeline setup and automation.',
            'description' => 'Build and automate production workflows.',
            'order_column' => 1,
            'is_active' => true,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Automation Engineering');

        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee('Automation Engineering');
    }

    public function test_inactive_services_are_hidden_from_public_pages(): void
    {
        Service::query()->create([
            'title' => 'Hidden Service',
            'slug' => 'hidden-service',
            'excerpt' => 'Should not be visible.',
            'description' => 'Internal service.',
            'order_column' => 1,
            'is_active' => false,
            'published_at' => null,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Hidden Service');

        $this->get(route('services.index'))
            ->assertOk()
            ->assertDontSee('Hidden Service');
    }
}
