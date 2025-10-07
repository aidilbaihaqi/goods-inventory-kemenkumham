<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\TransactionReportResource;
use App\Exports\TransactionExport;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListTransactionReports extends ListRecords
{
    protected static string $resource = TransactionReportResource::class;

    public function getTitle(): string
    {
        return 'Laporan Transaksi';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    DatePicker::make('date_from')
                        ->label('Dari Tanggal')
                        ->default(now()->subMonth()),
                    DatePicker::make('date_to')
                        ->label('Sampai Tanggal')
                        ->default(now()),
                    Select::make('type')
                        ->label('Jenis Transaksi')
                        ->options([
                            'in' => 'Masuk',
                            'out' => 'Keluar',
                        ])
                        ->placeholder('Semua Jenis'),
                ])
                ->action(function (array $data) {
                    $filters = array_filter($data);
                    $filename = 'laporan-transaksi-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
                    
                    return Excel::download(new TransactionExport($filters), $filename);
                }),
            Action::make('export_last_month')
                ->label('Export 1 Bulan')
                ->icon('heroicon-o-calendar')
                ->color('warning')
                ->action(function () {
                    $filters = [
                        'date_from' => now()->subMonth()->format('Y-m-d'),
                        'date_to' => now()->format('Y-m-d'),
                    ];
                    $filename = 'laporan-transaksi-1-bulan-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
                    
                    return Excel::download(new TransactionExport($filters), $filename);
                }),
            Action::make('export_last_day')
                ->label('Export 1 Hari')
                ->icon('heroicon-o-clock')
                ->color('info')
                ->action(function () {
                    $filters = [
                        'date_from' => now()->subDay()->format('Y-m-d'),
                        'date_to' => now()->format('Y-m-d'),
                    ];
                    $filename = 'laporan-transaksi-1-hari-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
                    
                    return Excel::download(new TransactionExport($filters), $filename);
                }),
        ];
    }
}