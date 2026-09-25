<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Kata Sandi')
                    ->description('Kosongkan untuk mempertahankan kata sandi saat ini (mode edit).')
                    ->schema([
                        TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->revealable()
                            ->rule(\Illuminate\Validation\Rules\Password::defaults())
                            // Required only when creating a new user.
                            ->required(fn (string $operation): bool => $operation === 'create')
                            // Hash on save; strip empty value on edit so it isn't overwritten.
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false)
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Hak Akses')
                    ->schema([
                        Toggle::make('is_admin')
                            ->label('Administrator')
                            ->helperText('Hanya admin yang bisa masuk ke panel ini.')
                            ->default(false),
                    ]),
            ]);
    }
}