<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Supplier')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Supplier')
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage('Nama supplier disalin!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('contact_person')
                            ->label('Kontak Person')
                            ->placeholder('Tidak ada kontak person'),
                        TextEntry::make('phone')
                            ->label('Nomor Telepon')
                            ->placeholder('Tidak ada nomor telepon')
                            ->copyable()
                            ->copyMessage('Nomor telepon disalin!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('Tidak ada email')
                            ->copyable()
                            ->copyMessage('Email disalin!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('address')
                            ->label('Alamat')
                            ->placeholder('Tidak ada alamat')
                            ->columnSpanFull(),
                        IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        TextEntry::make('transactions_count')
                            ->label('Total Transaksi')
                            ->badge()
                            ->color('info')
                            ->formatStateUsing(fn ($state) => $state . ' transaksi'),
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
