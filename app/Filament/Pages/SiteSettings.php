<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\UrlInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Setting;

class SiteSettings extends Page
{
    protected static string $routeName = 'settings.show';

    protected static ?string $title = 'Site Settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $icon = 'heroicon-o-cog-6-tooth';

    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Settings')
                ->tabs([
                    Tab::make('Site Identity')
                        ->icon('heroicon-o-academic-cap')
                        ->schema([
                            TextInput::make('site_name')
                                ->label('Nama Situs')
                                ->maxLength(100)
                                ->placeholder('e.g., KANG WILLY')
                                ->default(Setting::get('site_name')),
                            TextInput::make('site_tagline')
                                ->label('Tagline Situs')
                                ->maxLength(200)
                                ->placeholder('e.g., Vibe Coding')
                                ->default(Setting::get('site_tagline')),
                            Textarea::make('site_description')
                                ->label('Deskripsi Situs')
                                ->rows(3)
                                ->maxLength(1000)
                                ->placeholder('Deskripsi singkat tentang situs...')
                                ->default(Setting::get('site_description')),
                            Textarea::make('footer_text')
                                ->label('Footer Text')
                                ->rows(2)
                                ->maxLength(500)
                                ->placeholder('Teks untuk footer...')
                                ->default(Setting::get('footer_text')),
                            TagsInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->separator(',')
                                ->placeholder('keyword1, keyword2, ...')
                                ->default(Setting::get('meta_keywords')),
                            FileUpload::make('og_image')
                                ->label('Open Graph Image')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                ->disk('public')
                                ->directory('settings/og-image')
                                ->visibility('public')
                                ->maxSize(2048)
                                ->helperText('Image yang ditampilkan saat situs dibagikan di media sosial (maks 2MB)')
                                ->default(Setting::get('og_image')),
                            FileUpload::make('favicon')
                                ->label('Favicon')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                ->disk('public')
                                ->directory('settings/favicon')
                                ->visibility('public')
                                ->maxSize(500)
                                ->helperText('Ikon favicon situs (maks 500KB)')
                                ->default(Setting::get('favicon')),
                        ]),
                    Tab::make('Contact')
                        ->icon('heroicon-o-envelope')
                        ->schema([
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(100)
                                ->placeholder('contact@example.com')
                                ->default(Setting::get('email')),
                            TextInput::make('location')
                                ->label('Lokasi')
                                ->maxLength(200)
                                ->placeholder('Kota, Negara')
                                ->default(Setting::get('location')),
                        ]),
                    Tab::make('Social Media')
                        ->icon('heroicon-o-brain')
                        ->schema([
                            UrlInput::make('github')
                                ->label('GitHub')
                                ->placeholder('https://github.com/username')
                                ->default(Setting::get('github')),
                            UrlInput::make('linkedin')
                                ->label('LinkedIn')
                                ->placeholder('https://linkedin.com/in/username')
                                ->default(Setting::get('linkedin')),
                            UrlInput::make('twitter')
                                ->label('Twitter/X')
                                ->placeholder('https://twitter.com/username')
                                ->default(Setting::get('twitter')),
                            UrlInput::make('instagram')
                                ->label('Instagram')
                                ->placeholder('https://instagram.com/username')
                                ->default(Setting::get('instagram')),
                        ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $settings = [
            'site_name' => data_get($this->form->getState(), 'site_name'),
            'site_tagline' => data_get($this->form->getState(), 'site_tagline'),
            'site_description' => data_get($this->form->getState(), 'site_description'),
            'footer_text' => data_get($this->form->getState(), 'footer_text'),
            'meta_keywords' => data_get($this->form->getState(), 'meta_keywords'),
            'og_image' => data_get($this->form->getState(), 'og_image'),
            'favicon' => data_get($this->form->getState(), 'favicon'),
            'email' => data_get($this->form->getState(), 'email'),
            'location' => data_get($this->form->getState(), 'location'),
            'github' => data_get($this->form->getState(), 'github'),
            'linkedin' => data_get($this->form->getState(), 'linkedin'),
            'twitter' => data_get($this->form->getState(), 'twitter'),
            'instagram' => data_get($this->form->getState(), 'instagram'),
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        session()->flash('status', 'Settings saved successfully.');
    }
}