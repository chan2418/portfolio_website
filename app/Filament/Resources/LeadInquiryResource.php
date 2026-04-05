<?php

namespace App\Filament\Resources;

use App\Enums\LeadStage;
use App\Filament\Resources\LeadInquiryResource\Pages;
use App\Models\LeadInquiry;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadInquiryResource extends Resource
{
    protected static ?string $model = LeadInquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationGroup = 'Leads';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')->disabled(),
                TextInput::make('email')->disabled(),
                TextInput::make('phone')->disabled(),
                TextInput::make('company')->disabled(),
                TextInput::make('service_interest')->disabled(),
                TextInput::make('budget')->disabled(),
                TextInput::make('project_timeline')->disabled(),
                Textarea::make('message')->disabled()->rows(5),
                Select::make('stage')
                    ->options(LeadStage::options())
                    ->required(),
                Textarea::make('status_note')->rows(3),
                Placeholder::make('created_at')
                    ->content(fn (?LeadInquiry $record): string => $record?->created_at?->toDateTimeString() ?? '-'),
                Placeholder::make('source')
                    ->content(fn (?LeadInquiry $record): string => $record?->source ?? '-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('company')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('service_interest')
                    ->label('Interest')
                    ->toggleable(),
                TextColumn::make('stage')
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('stage')
                    ->options(LeadStage::options()),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLeadInquiries::route('/'),
        ];
    }
}
