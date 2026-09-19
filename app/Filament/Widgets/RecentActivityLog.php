<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentActivityLog extends TableWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(ActivityLog::query()->latest('updated_at')->limit(10))
            ->columns([
                TextColumn::make('type')->label('Jenis')->badge(),
                TextColumn::make('title')->label('Judul/Post')->wrap(),
                TextColumn::make('updated_at')->label('Dilihat')->dateTime()->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => match ($record->type) {
                        'Blog Post' => "/admin/blog-posts/{$record->record_id}/edit",
                        'Portofolio' => "/admin/portfolios/{$record->record_id}/edit",
                        'Layanan' => "/admin/services/{$record->record_id}/edit",
                        default => '#',
                    }),
            ]);
    }
}