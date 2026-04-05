<?php

namespace App\Filament\Resources;

use App\Enums\SeoPageType;
use App\Filament\Resources\SeoPageResource\Pages;
use App\Models\SeoPage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeoPageResource extends Resource
{
    protected static ?string $model = SeoPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'SEO & Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('page_type')
                    ->options(SeoPageType::options())
                    ->required(),
                TextInput::make('page_key')
                    ->required()
                    ->helperText('Examples: home, services, project slug, blog slug'),
                TextInput::make('meta_title')->maxLength(255),
                Textarea::make('meta_description')->rows(3),
                TextInput::make('og_title')->maxLength(255),
                Textarea::make('og_description')->rows(3),
                TextInput::make('og_image')->url()->maxLength(2048),
                TextInput::make('canonical_url')->url()->maxLength(2048),
                TextInput::make('robots_directive')->maxLength(120),
                Textarea::make('schema_markup')
                    ->rows(8)
                    ->helperText('JSON-LD schema block'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_type')
                    ->badge()
                    ->sortable(),
                TextColumn::make('page_key')
                    ->searchable(),
                TextColumn::make('meta_title')
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSeoPages::route('/'),
        ];
    }
}
