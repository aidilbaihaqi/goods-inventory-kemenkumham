<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap')
                            ->weight('bold')
                            ->copyable()
                            ->copyMessage('Nama pengguna disalin!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->copyMessage('Email disalin!')
                            ->copyMessageDuration(1500)
                            ->icon('heroicon-m-envelope'),
                        TextEntry::make('role')
                            ->label('Role')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'danger',
                                'manager' => 'warning',
                                'staff' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'admin' => 'Administrator',
                                'manager' => 'Manager',
                                'staff' => 'Staff',
                                default => $state,
                            }),
                        IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        TextEntry::make('email_verified_at')
                            ->label('Email Terverifikasi')
                            ->dateTime('d/m/Y H:i:s')
                            ->placeholder('Belum terverifikasi')
                            ->icon('heroicon-m-shield-check'),
                    ])->columns(2),
                Section::make('Informasi Sistem')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d/m/Y H:i:s')
                            ->icon('heroicon-m-calendar-days'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d/m/Y H:i:s')
                            ->icon('heroicon-m-clock'),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }
}