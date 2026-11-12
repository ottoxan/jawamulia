<?php

namespace App\Filament\Resources\MaterialTransactions\Schemas;

use App\Models\Material;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MaterialTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'name')
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->live(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->suffix(fn (Get $get): ?string => Material::find($get('material_id'))?->unit),
                Select::make('transaction_type')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('notes'),
                Hidden::make('created_by')
                    ->default(fn (): ?int => auth()->id()),
            ]);
    }
}
