<?php

namespace App\Filament\Resources;

use App\Enums\PublishStatus;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('client_name')->maxLength(255),
                TextInput::make('industry')->maxLength(255),
                Textarea::make('summary')
                    ->required()
                    ->rows(3),
                RichEditor::make('challenge')->columnSpanFull(),
                RichEditor::make('solution')->columnSpanFull(),
                RichEditor::make('results')->columnSpanFull(),
                TagsInput::make('tech_stack')
                    ->splitKeys([','])
                    ->placeholder('Laravel, MySQL, TailwindCSS'),
                KeyValue::make('metrics')
                    ->keyLabel('Metric')
                    ->valueLabel('Value')
                    ->addButtonLabel('Add metric'),
                TextInput::make('cover_image')
                    ->label('Cover Image (URL or Uploaded Path)')
                    ->placeholder('https://example.com/image.jpg or projects/covers/image.jpg')
                    ->dehydrateStateUsing(fn (mixed $state): ?string => is_string($state) && filled($state) ? $state : null)
                    ->helperText('Paste an image URL or upload a file below.'),
                FileUpload::make('cover_image_upload')
                    ->label('Upload Cover Image')
                    ->image()
                    ->disk('public')
                    ->directory('projects/covers')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1600')
                    ->imageResizeTargetHeight('900')
                    ->imageResizeUpscale(false)
                    ->imageEditor()
                    ->maxSize(10240)
                    ->default(function (?Project $record): ?string {
                        $current = (string) ($record?->cover_image ?? '');

                        if ($current === '' || Str::startsWith($current, ['http://', 'https://', '/'])) {
                            return null;
                        }

                        return $current;
                    })
                    ->afterStateUpdated(function (Set $set, mixed $state): void {
                        if (is_array($state)) {
                            $state = array_values($state)[0] ?? null;
                        }

                        if (! is_string($state) || blank($state)) {
                            return;
                        }

                        $set('cover_image', $state);
                    })
                    ->helperText('Uploaded files are auto-resized to 1600x900 before storage to reduce image size.'),
                TextInput::make('project_url')
                    ->url()
                    ->maxLength(2048),
                Select::make('status')
                    ->options(PublishStatus::options())
                    ->required()
                    ->default(PublishStatus::Draft->value),
                DateTimePicker::make('published_at')
                    ->helperText('Optional. Used for timeline/order display only. Visibility is controlled by Status.'),
                Toggle::make('is_featured'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client_name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PublishStatus::options()),
            ])
            ->defaultSort('published_at', 'desc')
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
            'index' => Pages\ManageProjects::route('/'),
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
