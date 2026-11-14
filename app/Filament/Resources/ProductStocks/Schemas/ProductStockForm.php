<?php

namespace App\Filament\Resources\ProductStocks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required(),
                Select::make('stock_type')
                    ->label('Tipe Stok')
                    ->native(false)
                    ->options([
                        'good' => 'Bagus (Good)',
                        'rejected' => 'Rusak (Rejected)',
                    ])
                    ->default('good')
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->type('number')
                    ->required(),
            ]);
    }
}
