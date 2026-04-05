<?php

namespace Tests\Feature;

use App\Mail\NewLeadInquiryMail;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_persists_lead_and_sends_notification(): void
    {
        Mail::fake();

        $payload = $this->validPayload();

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

        Mail::assertSent(NewLeadInquiryMail::class, function (NewLeadInquiryMail $mail): bool {
            $replyTo = $mail->envelope()->replyTo;

            if (! isset($replyTo[0]) || ! $replyTo[0] instanceof Address) {
                return false;
            }

            return $replyTo[0]->address === 'alex@example.com';
        });
    }

    public function test_honeypot_blocks_bot_submission(): void
    {
        $payload = $this->validPayload([
            'full_name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'website' => 'https://spam.example.com',
        ]);

        $this->post(route('contact.submit'), $payload)
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseCount('lead_inquiries', 0);
    }

    public function test_contact_submission_requires_captcha_token_when_enabled(): void
    {
        config()->set('services.captcha.enabled', true);
        config()->set('services.captcha.provider', 'hcaptcha');
        config()->set('services.captcha.hcaptcha.secret', 'secret-key');

        $this->post(route('contact.submit'), $this->validPayload(['captcha_token' => '']))
            ->assertRedirect()
            ->assertSessionHasErrors('captcha_token');

        $this->assertDatabaseCount('lead_inquiries', 0);
    }

    public function test_contact_submission_rejects_invalid_hcaptcha_token(): void
    {
        Mail::fake();

        config()->set('services.captcha.enabled', true);
        config()->set('services.captcha.provider', 'hcaptcha');
        config()->set('services.captcha.hcaptcha.secret', 'secret-key');

        Http::fake([
            'https://hcaptcha.com/siteverify' => Http::response(['success' => false], 200),
        ]);

        $this->post(route('contact.submit'), $this->validPayload(['captcha_token' => 'invalid-token']))
            ->assertRedirect()
            ->assertSessionHasErrors('captcha_token');

        $this->assertDatabaseCount('lead_inquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_contact_submission_accepts_valid_recaptcha_token(): void
    {
        Mail::fake();

        config()->set('services.captcha.enabled', true);
        config()->set('services.captcha.provider', 'recaptcha');
        config()->set('services.captcha.recaptcha.secret', 'secret-key');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $this->post(route('contact.submit'), $this->validPayload(['captcha_token' => 'valid-token']))
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertDatabaseHas('lead_inquiries', [
            'email' => 'alex@example.com',
            'stage' => 'new',
        ]);

        Mail::assertSent(NewLeadInquiryMail::class);
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
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
        ], $overrides);
    }
}
