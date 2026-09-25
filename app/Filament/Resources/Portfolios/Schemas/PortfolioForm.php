<?php

namespace App\Filament\Resources\Portfolios\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PortfolioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Portfolio Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(2000),
                        TextInput::make('url')
                            ->label('Project URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com'),
                        TextInput::make('category')
                            ->maxLength(100)
                            ->placeholder('e.g., Web App, Mobile App, UI/UX'),
                        TextInput::make('tech_stack')
                            ->label('Tech Stack')
                            ->maxLength(255)
                            ->placeholder('e.g., Laravel, Vue.js, MySQL'),
                    ])->columns(2),

                Section::make('Media & Settings')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Cover Image')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->disk('public')
                            ->directory('portfolio')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Maks 2 MB.'),
                        Toggle::make('is_featured')
                            ->label('Featured Project'),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Urutan tampilan di halaman utama'),
                    ])->columns(2),
            ]);
    }
}
