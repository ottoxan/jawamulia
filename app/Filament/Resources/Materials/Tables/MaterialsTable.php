<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Bahan'),
                TextColumn::make('material_type')
                    ->label('Jenis Bahan')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'baku' => 'Bahan Baku',
                        'penolong' => 'Bahan Penolong',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit')->label('Satuan'),
                TextColumn::make('description')->label('Deskripsi'),
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
