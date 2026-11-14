<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Produk')
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: Product::class,
                        column: 'code',
                        ignorable: fn (?Product $record): ?Product => $record,
                    ),
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),
                Select::make('product_type')
                    ->label('Jenis Produk')
                    ->native(false)
                    ->options([
                        'semi-finished' => 'Setengah Jadi',
                        'finished' => 'Barang Jadi',
                    ])
                    ->required(),
                TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->native(false)
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ])
                    ->default('active')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpan(2),
            ]);
    }
}
