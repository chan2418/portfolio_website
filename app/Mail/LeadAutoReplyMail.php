<?php

namespace App\Mail;

use App\Models\LeadInquiry;
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
        return new Content(
            view: 'emails.leads.auto-reply',
            with: [
                'leadInquiry' => $this->leadInquiry,
                'siteName' => $this->siteName,
                'contactEmail' => $this->contactEmail,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
