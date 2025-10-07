<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Barang')
                            ->copyable()
                            ->copyMessage('Kode berhasil disalin!')
                            ->weight('bold'),
                        TextEntry::make('name')
                            ->label('Nama Barang')
                            ->weight('bold'),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tidak ada deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Kategori & Satuan')
                    ->schema([
                        TextEntry::make('category.name')
                            ->label('Kategori')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('unit.name')
                            ->label('Satuan')
                            ->badge()
                            ->color('gray'),
                    ])->columns(2),

                Section::make('Foto Barang')
                    ->schema([
                        ImageEntry::make('photo')
                            ->label('Foto')
                            ->disk('public')
                            ->height(200)
                            ->width(200)
                            ->defaultImageUrl(url('/images/no-image.png'))
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Informasi Stok')
                    ->schema([
                        TextEntry::make('current_stock')
                            ->label('Stok Saat Ini')
                            ->numeric(decimalPlaces: 2)
                            ->color(fn ($record) => $record->isLowStock() ? 'danger' : 'success')
                            ->weight(fn ($record) => $record->isLowStock() ? 'bold' : 'normal')
                            ->icon(fn ($record) => $record->isLowStock() ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle'),
                        TextEntry::make('min_stock')
                            ->label('Stok Minimum')
                            ->numeric(),
                        TextEntry::make('current_value')
                            ->label('Nilai Saat Ini')
                            ->money('IDR'),
                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])->columns(2),

                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
