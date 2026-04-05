<?php

namespace App\Mail;

use App\Models\LeadInquiry;
use App\Support\SiteSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LeadInquiry $leadInquiry)
    {
    }

    public function envelope(): Envelope
    {
        $replyTo = [];

        if (filter_var($this->leadInquiry->email, FILTER_VALIDATE_EMAIL)) {
            $replyTo[] = new Address(
                $this->leadInquiry->email,
                $this->leadInquiry->full_name ?: null
            );
        }

        return new Envelope(
            subject: 'New Lead Inquiry: '.$this->leadInquiry->full_name,
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        $siteName = (string) SiteSettings::get('site_name', config('app.name', 'Portfolio Core'));
        $logoUrl = SiteSettings::get('profile_photo_url');
        $serviceInterest = $this->leadInquiry->service_interest ?: 'Service not specified';
        $budget = $this->leadInquiry->budget ?: 'Budget not specified';

        return new Content(
            view: 'emails.leads.new',
            with: [
                'leadInquiry' => $this->leadInquiry,
                'siteName' => $siteName,
                'logoUrl' => filled($logoUrl) ? (string) $logoUrl : null,
                'preheader' => 'New inquiry from '.$this->leadInquiry->full_name,
                'headerTitle' => 'New inbound lead inquiry',
                'headerSubtitle' => $serviceInterest.' | '.$budget,
                'adminLeadUrl' => $this->resolveAdminLeadUrl(),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    protected function resolveAdminLeadUrl(): ?string
    {
        try {
            return route('filament.admin.resources.lead-inquiries.index');
        } catch (\Throwable) {
            $baseUrl = rtrim((string) config('app.url', ''), '/');

            return $baseUrl !== '' ? $baseUrl.'/admin/lead-inquiries' : null;
        }
    }
}
