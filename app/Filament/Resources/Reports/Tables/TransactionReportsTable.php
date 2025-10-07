<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TransactionReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'in',
                        'danger' => 'out',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                        default => $state,
                    }),
                TextColumn::make('item.name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item.category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item.unit.symbol')
                    ->label('Satuan')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('item.supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Keterangan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('date_to')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators[] = 'Dari: ' . Carbon::parse($data['date_from'])->format('d/m/Y');
                        }
                        if ($data['date_to'] ?? null) {
                            $indicators[] = 'Sampai: ' . Carbon::parse($data['date_to'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),
                SelectFilter::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                    ]),
                Filter::make('last_month')
                    ->label('1 Bulan Terakhir')
                    ->query(fn (Builder $query): Builder => $query->where('transaction_date', '>=', now()->subMonth()))
                    ->toggle(),
                Filter::make('last_day')
                    ->label('1 Hari Terakhir')
                    ->query(fn (Builder $query): Builder => $query->where('transaction_date', '>=', now()->subDay()))
                    ->toggle(),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}