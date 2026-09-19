<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PendingDraftsList extends TableWidget
{
    protected static ?string $heading = 'Draft Blog Post Menunggu';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BlogPost::query()
                    ->where(fn ($q) => $q->where('is_published', false)->orWhereNull('published_at'))
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (BlogPost $record) => '/admin/blog-posts/' . $record->id . '/edit'),
            ]);
    }
}