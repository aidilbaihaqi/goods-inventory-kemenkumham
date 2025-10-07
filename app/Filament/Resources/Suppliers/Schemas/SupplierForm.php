<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;

class SupplierForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Supplier')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Supplier')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Masukkan nama supplier'),
                        TextInput::make('contact_person')
                            ->label('Nama Kontak')
                            ->maxLength(255)
                            ->placeholder('Masukkan nama kontak person'),
                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('Masukkan nomor telepon'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Masukkan alamat email'),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->placeholder('Masukkan alamat lengkap supplier')
                            ->rows(3)
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Supplier yang tidak aktif tidak akan muncul dalam pilihan saat menambah transaksi baru'),
                    ])->columns(2),
            ]);
    }
}
