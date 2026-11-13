<?php

namespace App\Filament\Resources\MaterialTransactions\Schemas;

use App\Models\Material;
use App\Models\MaterialTransaction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
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
                    ->columnSpan(2)
                    ->live(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                ->columnSpan(1)
                    ->suffix(fn (Get $get): ?string => Material::find($get('material_id'))?->unit),
                ToggleButtons::make('transaction_type')
                    ->label('Jenis Transaksi')
                ->columnSpan(1)
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                    ])
                    ->icons([
                        'in' => 'heroicon-m-arrow-right-end-on-rectangle',
                        'out' => 'heroicon-m-arrow-left-start-on-rectangle',
                    ])
                    ->colors([
                        'in' => 'success',
                        'out' => 'danger',
                    ])
                    ->inline()
                    ->required(),
                Textarea::make('notes')
                    ->columnSpan(2)
                    ->label('Catatan'),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M y H:i')
                    ->visible(fn (?MaterialTransaction $record): bool => filled($record?->created_at)),
                Hidden::make('created_by')
                    ->default(fn (): ?int => auth()->id()),
            ])->columns(2);
    }
}
