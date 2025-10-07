<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Transaksi Terbaru';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->with(['item', 'supplier', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference_no')
                    ->label('No. Referensi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('No. referensi disalin!')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Barang')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                        'penyesuaian' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'masuk' => 'Masuk',
                        'keluar' => 'Keluar',
                        'penyesuaian' => 'Penyesuaian',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable()
                    ->suffix(fn (Transaction $record): string => ' ' . $record->item->unit->name),
                Tables\Columns\TextColumn::make('total_value')
                    ->label('Total Nilai')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Tidak ada supplier')
                    ->limit(20),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->limit(15),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Transaction $record): string => route('filament.admin.resources.transactions.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Belum ada transaksi')
            ->emptyStateDescription('Transaksi akan muncul di sini setelah ada aktivitas.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated(false);
    }
}