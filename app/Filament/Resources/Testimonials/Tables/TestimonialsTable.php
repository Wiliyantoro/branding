<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client_company')
                    ->label('Company'),
                TextColumn::make('client_title')
                    ->label('Title')
                    ->limit(20),
                TextColumn::make('content')
                    ->limit(50),
                TextColumn::make('rating')
                    ->badge()
                    ->color(fn (string $state): string => match(true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'warning',
                        default => 'danger',
                    }),
                IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
