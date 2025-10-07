<?php

namespace App\Filament\Resources\Reports;

use App\Models\Transaction;
use App\Filament\Resources\Reports\Tables\TransactionReportsTable;
use App\Filament\Resources\Reports\Pages;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use UnitEnum;
use BackedEnum;

class TransactionReportResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Transaksi';

    protected static ?string $slug = 'reports/transactions';

    protected static UnitEnum | string | null $navigationGroup = 'Laporan';

    public static function table(Table $table): Table
    {
        return TransactionReportsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionReports::route('/'),
        ];
    }
}