<?php

namespace App\Filament\Resources\LeadInquiryResource\Pages;

use App\Filament\Resources\LeadInquiryResource;
use Filament\Resources\Pages\ManageRecords;

class ManageLeadInquiries extends ManageRecords
{
    protected static string $resource = LeadInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
