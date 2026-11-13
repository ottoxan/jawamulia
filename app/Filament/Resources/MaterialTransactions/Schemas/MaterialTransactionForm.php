<?php

namespace App\Filament\Resources\MaterialTransactions\Schemas;

use App\Models\Material;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MaterialTransactionForm
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
                    ->live(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->suffix(fn(Get $get): ?string => Material::find($get('material_id'))?->unit),
                Select::make('transaction_type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('notes')
                    ->label('Catatan'),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M y H:i'),
                Hidden::make('created_by')
                    ->default(fn(): ?int => auth()->id()),
            ]);
    }
}
