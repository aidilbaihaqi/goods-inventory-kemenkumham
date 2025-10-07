<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Satuan')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Satuan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Masukkan nama satuan (contoh: Kilogram, Meter, Buah)'),
                        TextInput::make('symbol')
                            ->label('Simbol')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Masukkan simbol satuan (contoh: kg, m, pcs)'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Masukkan deskripsi satuan (opsional)')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Satuan yang tidak aktif tidak akan muncul dalam pilihan saat menambah barang baru'),
                    ])->columns(2),
            ]);
    }
}
