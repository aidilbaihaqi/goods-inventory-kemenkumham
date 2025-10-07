<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masukkan nama lengkap pengguna'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Masukkan alamat email'),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->placeholder('Masukkan password (minimal 8 karakter)')
                            ->helperText('Kosongkan jika tidak ingin mengubah password (hanya untuk edit)'),
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->same('password')
                            ->placeholder('Ulangi password yang sama'),
                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Administrator',
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                            ])
                            ->default('staff')
                            ->required()
                            ->helperText('Administrator: Akses penuh, Manager: Akses laporan dan manajemen, Staff: Akses terbatas'),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->helperText('Pengguna yang tidak aktif tidak dapat login ke sistem'),
                    ])->columns(2),
            ]);
    }
}