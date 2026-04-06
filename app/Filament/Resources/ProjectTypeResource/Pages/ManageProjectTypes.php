<?php

namespace App\Filament\Resources\ProjectTypeResource\Pages;

use App\Filament\Resources\ProjectTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProjectTypes extends ManageRecords
{
    protected static string $resource = ProjectTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(fn (array $data): array => ProjectTypeResource::normalizeCoverImageData($data)),
        ];
    }
}
