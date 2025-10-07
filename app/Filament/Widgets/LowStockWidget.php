<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockWidget extends BaseWidget
{
    protected static ?string $heading = 'Barang Stok Rendah';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Item::query()
                    ->whereRaw('current_stock <= min_stock')
                    ->where('is_active', true)
                    ->orderBy('current_stock', 'asc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('current_stock')
                    ->label('Stok Saat Ini')
                    ->numeric()
                    ->sortable()
                    ->color('danger')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('min_stock')
                    ->label('Stok Minimum')
                    ->numeric()
                    ->sortable()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Satuan')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('difference')
                    ->label('Selisih')
                    ->getStateUsing(function (Item $record): string {
                        $diff = $record->current_stock - $record->min_stock;
                        return $diff . ' ' . $record->unit->name;
                    })
                    ->color('danger')
                    ->weight('bold'),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Item $record): string => route('filament.admin.resources.items.view', $record))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Tidak ada barang dengan stok rendah')
            ->emptyStateDescription('Semua barang memiliki stok yang mencukupi.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated(false);
    }
}