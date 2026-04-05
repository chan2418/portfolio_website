<?php

namespace App\Filament\Widgets;

use App\Enums\LeadStage;
use App\Models\LeadInquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadPipelineStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New', (string) LeadInquiry::query()->where('stage', LeadStage::New->value)->count())
                ->color('gray'),
            Stat::make('Contacted', (string) LeadInquiry::query()->where('stage', LeadStage::Contacted->value)->count())
                ->color('info'),
            Stat::make('Qualified', (string) LeadInquiry::query()->where('stage', LeadStage::Qualified->value)->count())
                ->color('success'),
            Stat::make('Proposal', (string) LeadInquiry::query()->where('stage', LeadStage::Proposal->value)->count())
                ->color('warning'),
            Stat::make('Closed', (string) LeadInquiry::query()->where('stage', LeadStage::Closed->value)->count())
                ->color('primary'),
        ];
    }
}
