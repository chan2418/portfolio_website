<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\SiteSettings;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Support\Enums\Alignment;

class BrandProfile extends Page
{
    use InteractsWithFormActions;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'SEO & Settings';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Brand Profile';

    protected static ?string $navigationLabel = 'Brand Profile';

    protected static ?string $slug = 'brand-profile';

    protected static string $view = 'filament.pages.brand-profile';

    public function mount(): void
    {
        $this->fillForm();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identity')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Site Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('brand_person_name')
                            ->label('Your Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('brand_role')
                            ->label('Role / Title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('profile_photo_url')
                            ->label('Profile Photo URL')
                            ->url()
                            ->maxLength(2048),
                    ])
                    ->columns(2),
                Section::make('Messaging')
                    ->schema([
                        Textarea::make('site_tagline')
                            ->label('Site Tagline')
                            ->rows(2)
                            ->required(),
                        Textarea::make('brand_short_bio')
                            ->label('Short Bio')
                            ->rows(3)
                            ->required(),
                        Textarea::make('brand_long_bio')
                            ->label('Long Bio')
                            ->rows(5)
                            ->required(),
                    ])
                    ->columns(1),
                Section::make('Contact & Social')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Public Contact Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('social_linkedin_url')
                            ->label('LinkedIn URL')
                            ->url()
                            ->maxLength(2048),
                        TextInput::make('social_github_url')
                            ->label('GitHub URL')
                            ->url()
                            ->maxLength(2048),
                        TextInput::make('social_x_url')
                            ->label('X URL')
                            ->url()
                            ->maxLength(2048),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $rows = [];
        foreach ($this->settingMap() as $key => $meta) {
            $rows[] = [
                'key' => $key,
                'value' => $data[$key] ?? null,
                'group' => $meta['group'],
                'label' => $meta['label'],
                'updated_at' => now(),
            ];
        }

        SiteSetting::query()->upsert($rows, ['key'], ['value', 'group', 'label', 'updated_at']);
        SiteSettings::clear();

        Notification::make()
            ->success()
            ->title('Brand profile updated')
            ->body('Your personal brand settings are now live on the public site.')
            ->send();
    }

    /**
     * @return array<string, array{group: string, label: string, default: string}>
     */
    protected function settingMap(): array
    {
        return [
            'site_name' => ['group' => 'branding', 'label' => 'Site Name', 'default' => 'Portfolio Core Studio'],
            'site_tagline' => ['group' => 'branding', 'label' => 'Site Tagline', 'default' => 'Product-grade portfolio systems that convert traffic into clients.'],
            'brand_person_name' => ['group' => 'branding', 'label' => 'Person Name', 'default' => 'Your Name'],
            'brand_role' => ['group' => 'branding', 'label' => 'Primary Role', 'default' => 'Laravel Developer & Product Engineer'],
            'brand_short_bio' => ['group' => 'branding', 'label' => 'Short Bio', 'default' => 'I build high-performing portfolio and product websites that help personal brands turn visitors into clients.'],
            'brand_long_bio' => ['group' => 'branding', 'label' => 'Long Bio', 'default' => 'I specialize in Laravel development, conversion-focused UX, and SEO-ready content systems.'],
            'profile_photo_url' => ['group' => 'branding', 'label' => 'Profile Photo URL', 'default' => ''],
            'contact_email' => ['group' => 'contact', 'label' => 'Public Contact Email', 'default' => ''],
            'social_linkedin_url' => ['group' => 'social', 'label' => 'LinkedIn URL', 'default' => ''],
            'social_github_url' => ['group' => 'social', 'label' => 'GitHub URL', 'default' => ''],
            'social_x_url' => ['group' => 'social', 'label' => 'X URL', 'default' => ''],
        ];
    }

    protected function fillForm(): void
    {
        $settingMap = $this->settingMap();

        $defaults = collect($settingMap)
            ->mapWithKeys(fn (array $meta, string $key): array => [$key => $meta['default']])
            ->all();

        $stored = SiteSetting::query()
            ->whereIn('key', array_keys($settingMap))
            ->pluck('value', 'key')
            ->toArray();

        $this->form->fill(array_merge($defaults, $stored));
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Brand Profile')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public function getFormActionsAlignment(): string | Alignment
    {
        return Alignment::Start;
    }
}
