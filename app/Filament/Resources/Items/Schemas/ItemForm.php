<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Services\ImageOptimizer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode Barang')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        TextInput::make('name')
                            ->label('Nama Barang')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),

                Section::make('Kategori & Satuan')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('unit_id')
                            ->label('Satuan')
                            ->relationship('unit', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Section::make('Foto Barang')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '4:3',
                                '16:9',
                            ])
                            ->directory('items')
                            ->disk('public')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->maxSize(5120) // 5MB
                            ->getUploadedFileNameForStorageUsing(
                                fn (UploadedFile $file): string => (string) str($file->getClientOriginalName())
                                    ->prepend('item-')
                                    ->prepend(now()->format('Y-m-d-H-i-s-'))
                            )
                            ->helperText('Format: JPEG, PNG, GIF, WebP. Maksimal 5MB. Dimensi minimal 100x100px.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Stok & Nilai')
                    ->schema([
                        TextInput::make('min_stock')
                            ->label('Stok Minimum')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        TextInput::make('current_stock')
                            ->label('Stok Saat Ini')
                            ->required()
                            ->numeric()
                            ->default(0.0)
                            ->minValue(0)
                            ->step(0.01),
                        TextInput::make('current_value')
                            ->label('Nilai Saat Ini')
                            ->required()
                            ->numeric()
                            ->default(0.0)
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('Rp'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }
}
