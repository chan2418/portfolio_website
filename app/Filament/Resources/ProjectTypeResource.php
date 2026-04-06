<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectTypeResource\Pages;
use App\Models\ProjectType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectTypeResource extends Resource
{
    protected static ?string $model = ProjectType::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(500),
                TextInput::make('cover_image')
                    ->label('Type Cover Image (URL or Uploaded Path)')
                    ->placeholder('https://example.com/type-cover.jpg or project-types/covers/type-cover.jpg')
                    ->dehydrateStateUsing(fn (mixed $state): ?string => is_string($state) && filled($state) ? $state : null)
                    ->helperText('Paste image URL or upload below.'),
                FileUpload::make('cover_image_upload')
                    ->label('Upload Type Cover Image')
                    ->image()
                    ->disk('public')
                    ->directory('project-types/covers')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1400')
                    ->imageResizeTargetHeight('900')
                    ->imageResizeUpscale(false)
                    ->imageEditor()
                    ->maxSize(10240)
                    ->default(function (?ProjectType $record): ?string {
                        $current = (string) ($record?->cover_image ?? '');

                        if ($current === '' || Str::startsWith($current, ['http://', 'https://', '/'])) {
                            return null;
                        }

                        return $current;
                    })
                    ->helperText('Used on home project-type cards. Auto-resized before storage.'),
                TextInput::make('order_column')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('order_column')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
            ])
            ->defaultSort('order_column')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(fn (array $data): array => static::normalizeCoverImageData($data)),
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
            'index' => Pages\ManageProjectTypes::route('/'),
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function normalizeCoverImageData(array $data): array
    {
        $uploadValue = $data['cover_image_upload'] ?? null;

        if (is_array($uploadValue)) {
            $uploadValue = array_values($uploadValue)[0] ?? null;
        }

        if (is_string($uploadValue) && filled($uploadValue)) {
            $data['cover_image'] = $uploadValue;
        }

        unset($data['cover_image_upload']);

        return $data;
    }
}
