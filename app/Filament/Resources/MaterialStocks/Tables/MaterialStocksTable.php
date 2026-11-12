<?php

namespace App\Filament\Resources\MaterialStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup('material.material_type')
            ->columns([
                TextColumn::make('Id')
                    ->rowIndex(),
                TextColumn::make('material.name')
                    ->label('Nama Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('material.material_type')
                    ->label('Jenis Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state, $record): string => "{$state} {$record->material?->unit}")
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
