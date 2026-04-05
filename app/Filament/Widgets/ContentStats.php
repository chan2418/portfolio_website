<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Services', (string) Service::query()->count())
                ->description('Published services and offers')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('success'),
            Stat::make('Case Studies', (string) Project::query()->count())
                ->description('Portfolio projects tracked')
                ->descriptionIcon('heroicon-o-folder')
                ->color('info'),
            Stat::make('Blog Posts', (string) BlogPost::query()->count())
                ->description('SEO content articles')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning'),
            Stat::make('Testimonials', (string) Testimonial::query()->count())
                ->description('Trust signal entries')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color('primary'),
        ];
    }
}
