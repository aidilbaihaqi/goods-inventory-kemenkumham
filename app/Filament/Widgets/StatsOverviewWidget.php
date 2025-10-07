<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalItems = Item::count();
        $activeItems = Item::where('is_active', true)->count();
        $lowStockItems = Item::whereRaw('current_stock <= min_stock')->count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalUnits = Unit::count();
        $totalTransactions = Transaction::count();
        $totalValue = Transaction::sum('total_value');

        return [
            Stat::make('Total Barang', $totalItems)
                ->description('Jumlah total barang dalam sistem')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Barang Aktif', $activeItems)
                ->description('Barang yang sedang aktif')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([3, 5, 8, 12, 15, 18, 20]),

            Stat::make('Stok Rendah', $lowStockItems)
                ->description('Barang dengan stok di bawah minimum')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockItems > 0 ? 'danger' : 'success')
                ->chart([2, 1, 3, 2, 4, 1, 2]),

            Stat::make('Kategori', $totalCategories)
                ->description('Total kategori barang')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info')
                ->chart([1, 2, 3, 4, 5, 6, 7]),

            Stat::make('Supplier', $totalSuppliers)
                ->description('Total supplier terdaftar')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('warning')
                ->chart([2, 4, 6, 8, 10, 12, 14]),

            Stat::make('Nilai Transaksi', 'Rp ' . number_format($totalValue, 0, ',', '.'))
                ->description('Total nilai semua transaksi')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([100, 200, 300, 400, 500, 600, 700]),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}