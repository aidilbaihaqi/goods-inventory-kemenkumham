<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\CustomAccountWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TransactionChartWidget;
use App\Filament\Widgets\LowStockWidget;
use App\Filament\Widgets\RecentTransactionsWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            CustomAccountWidget::class,
            StatsOverviewWidget::class,
            TransactionChartWidget::class,
            LowStockWidget::class,
            RecentTransactionsWidget::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }
}