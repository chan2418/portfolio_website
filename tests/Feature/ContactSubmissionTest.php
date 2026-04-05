<?php

namespace Tests\Feature;

use App\Mail\NewLeadInquiryMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_persists_lead_and_sends_notification(): void
    {
        Mail::fake();

        $payload = [
            'full_name' => 'Alex Customer',
            'email' => 'alex@example.com',
            'phone' => '+1-555-0199',
            'company' => 'Northwind Co',
            'budget' => '$5k-$10k',
            'service_interest' => 'Laravel Product Engineering',
            'project_timeline' => '4-6 weeks',
            'message' => 'We need a complete portfolio system with admin CMS, case studies, blog, and lead pipeline.',
            'website' => '',
            'captcha_token' => '',
        ];

        $this->post(route('contact.submit'), $payload)
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('lead_inquiries', [
            'email' => 'alex@example.com',
            'stage' => 'new',
        ]);

        $this->assertDatabaseHas('lead_activities', [
            'activity_type' => 'created',
        ]);

        Mail::assertSent(NewLeadInquiryMail::class);
    }

    public function test_honeypot_blocks_bot_submission(): void
    {
        $payload = [
            'full_name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'message' => 'This should be ignored because honeypot field is filled and we should skip lead creation.',
            'website' => 'https://spam.example.com',
        ];

        $this->post(route('contact.submit'), $payload)
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseCount('lead_inquiries', 0);
    }
}
