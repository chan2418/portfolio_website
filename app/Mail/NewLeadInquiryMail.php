<?php

namespace App\Mail;

use App\Models\LeadInquiry;
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
        return new Content(
            view: 'emails.leads.new',
            with: [
                'leadInquiry' => $this->leadInquiry,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
