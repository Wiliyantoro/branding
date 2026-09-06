<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setting Details')
                    ->schema([
                        Select::make('group')
                            ->required()
                            ->default('general')
                            ->options([
                                'general' => 'General',
                                'contact' => 'Contact',
                                'social' => 'Social',
                            ]),
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->regex('/^[a-z0-9_]+$/')
                            ->helperText('huruf kecil, angka, underscore')
                            ->placeholder('e.g., site_name, site_description'),
                        Textarea::make('value')
                            ->rows(3)
                            ->maxLength(2000)
                            ->placeholder('Setting value...'),
                    ])->columns(2),
            ]);
    }
}
