<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class UnitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Satuan')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Satuan')
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage('Nama satuan disalin!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('symbol')
                            ->label('Simbol')
                            ->badge()
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Simbol disalin!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Tidak ada deskripsi')
                            ->columnSpanFull(),
                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        TextEntry::make('items_count')
                            ->label('Jumlah Barang')
                            ->badge()
                            ->color('info')
                            ->formatStateUsing(fn ($state) => $state . ' barang'),
                    ])->columns(2),
                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d/m/Y H:i:s'),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }
}
