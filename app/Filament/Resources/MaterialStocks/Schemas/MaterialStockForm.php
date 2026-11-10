<?php

namespace App\Filament\Resources\MaterialStocks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;


class MaterialStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->type('number')
                    ->required(),
            ]);
    }
}
