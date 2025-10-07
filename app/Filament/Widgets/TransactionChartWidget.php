<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TransactionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Transaksi Bulanan';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $now = Carbon::now();
        $months = [];
        $inData = [];
        $outData = [];

        // Get data for last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthName = $month->format('M Y');
            $months[] = $monthName;

            // Count IN transactions for this month
            $inCount = Transaction::where('type', 'in')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->count();
            $inData[] = $inCount;

            // Count OUT transactions for this month
            $outCount = Transaction::where('type', 'out')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->count();
            $outData[] = $outCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Barang Masuk',
                    'data' => $inData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
                [
                    'label' => 'Barang Keluar',
                    'data' => $outData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
        ];
    }
}