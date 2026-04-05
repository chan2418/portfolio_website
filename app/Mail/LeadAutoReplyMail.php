<?php

namespace App\Mail;

use App\Models\LeadInquiry;
use App\Support\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadAutoReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LeadInquiry $leadInquiry,
        public string $siteName,
        public ?string $contactEmail = null
    ) {
    }

    public function envelope(): Envelope
    {
        $replyTo = [];

        if (filled($this->contactEmail) && filter_var($this->contactEmail, FILTER_VALIDATE_EMAIL)) {
            $replyTo[] = new Address($this->contactEmail, $this->siteName);
        }

        return new Envelope(
            subject: "Thanks for reaching out to {$this->siteName}",
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        $logoUrl = SiteSettings::get('profile_photo_url');
        $caseStudiesUrl = $this->routeOrFallback('case-studies.index', '/case-studies');
        $siteUrl = $this->routeOrFallback('home', '/');
        $contactUrl = $this->routeOrFallback('contact.show', '/contact');

        return new Content(
            view: 'emails.leads.auto-reply',
            with: [
                'leadInquiry' => $this->leadInquiry,
                'siteName' => $this->siteName,
                'contactEmail' => $this->contactEmail,
                'logoUrl' => filled($logoUrl) ? (string) $logoUrl : null,
                'preheader' => "We received your inquiry at {$this->siteName}.",
                'headerTitle' => 'Inquiry received successfully',
                'headerSubtitle' => 'Our team is reviewing your message and will get back to you with next steps shortly.',
                'caseStudiesUrl' => $caseStudiesUrl,
                'siteUrl' => $siteUrl,
                'contactUrl' => $contactUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected function routeOrFallback(string $routeName, string $fallbackPath): ?string
    {
        try {
            return route($routeName);
        } catch (\Throwable) {
            $baseUrl = rtrim((string) config('app.url', ''), '/');

            return $baseUrl !== '' ? $baseUrl.$fallbackPath : null;
        }
    }
}
