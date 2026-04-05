<?php

namespace App\Filament\Pages;

use App\Support\LaunchReadinessChecker;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class LaunchReadiness extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'SEO & Settings';

    protected static ?int $navigationSort = 6;

    protected static ?string $title = 'Launch Readiness';

    protected static ?string $navigationLabel = 'Launch Readiness';

    protected static ?string $slug = 'launch-readiness';

    protected static string $view = 'filament.pages.launch-readiness';

    /**
     * @return array{
     *   score: int,
     *   summary: array{total: int, pass: int, warn: int, fail: int},
     *   checks: array<int, array{key: string, label: string, status: string, message: string}>
     * }
     */
    public function getReport(): array
    {
        return app(LaunchReadinessChecker::class)->build();
    }

    /**
     * @return array<int, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateSitemap')
                ->label('Generate Sitemap')
                ->color('gray')
                ->action(function (): void {
                    Artisan::call('app:generate-sitemap');

                    Notification::make()
                        ->success()
                        ->title('Sitemap generated')
                        ->body('The latest sitemap has been generated at public/sitemap.xml.')
                        ->send();
                }),
            Action::make('runLaunchCheck')
                ->label('Run CLI Check')
                ->color('gray')
                ->action(function (): void {
                    Artisan::call('app:launch-check');

                    Notification::make()
                        ->title('Launch check completed')
                        ->body('Command output is available in your terminal logs.')
                        ->send();
                }),
        ];
    }
}
