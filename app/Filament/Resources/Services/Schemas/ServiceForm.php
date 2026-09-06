<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->required()
                            ->rows(3)
                            ->maxLength(2000),
                        // Rendered as <i class="fas fa-{icon}"> on the public page.
                        TextInput::make('icon')
                            ->label('Icon (Font Awesome, tanpa prefix "fa-")')
                            ->maxLength(50)
                            ->regex('/^[a-z0-9-]+$/')
                            ->default('code')
                            ->placeholder('e.g., code, mobile-screen, paint-brush'),
                        TextInput::make('price')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp'),
                        TextInput::make('price_label')
                            ->label('Price Label')
                            ->maxLength(50)
                            ->placeholder('e.g., Mulai dari, Per projek'),
                    ])->columns(2),

                Section::make('Settings')
                    ->schema([
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
