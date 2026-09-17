<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use Filament\Widgets\ChartWidget;

class BlogCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Artikel per Kategori';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $categories = BlogPost::selectRaw('COALESCE(category, "Uncategorized") as cat, count(*) as total')
            ->groupBy('cat')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Artikel',
                    'data' => $categories->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#64748b',
                    ],
                ],
            ],
            'labels' => $categories->pluck('cat')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
