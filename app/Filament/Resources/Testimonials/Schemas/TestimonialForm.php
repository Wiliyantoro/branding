<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Information')
                    ->schema([
                        TextInput::make('client_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('client_title')
                            ->label('Job Title')
                            ->maxLength(150)
                            ->placeholder('e.g., CEO, Marketing Director'),
                        TextInput::make('client_company')
                            ->label('Company')
                            ->maxLength(150)
                            ->placeholder('e.g., PT Maju Jaya'),
                        FileUpload::make('client_avatar')
                            ->label('Avatar')
                            ->image()
                            ->avatar()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(1024)
                            ->helperText('Maks 1 MB.'),
                    ])->columns(2),

                Section::make('Testimonial')
                    ->schema([
                        Textarea::make('content')
                            ->required()
                            ->rows(4)
                            ->maxLength(2000)
                            ->placeholder('Write the testimonial content...'),
                        TextInput::make('rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5)
                            ->suffix('/ 5'),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ])->columns(2),
            ]);
    }
}
