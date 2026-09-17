<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\Portfolio;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Artikel Blog', BlogPost::count())
                ->description('Artikel rilis publik: ' . BlogPost::published()->count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
                
            Stat::make('Total Views Blog', BlogPost::sum('views_count'))
                ->description('Akumulasi tayangan artikel')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),
                
            Stat::make('Portofolio', Portfolio::count())
                ->description('Portofolio aktif: ' . Portfolio::published()->count())
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),
        ];
    }
}
