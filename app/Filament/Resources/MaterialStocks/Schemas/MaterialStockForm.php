<?php

namespace App\Filament\Resources\MaterialStocks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('material_id')
                    ->label('Bahan')
                    ->relationship('material', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->type('number')
                    ->required(),
            ]);

    }
}
