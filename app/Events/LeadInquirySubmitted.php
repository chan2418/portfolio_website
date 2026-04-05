<?php

namespace App\Events;

use App\Models\LeadInquiry;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadInquirySubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public LeadInquiry $leadInquiry)
    {
    }
}
