<?php

namespace App\Filament\Resources\BomItems\Tables;

use App\Models\BomItem;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class BomItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Bill of Material')
            ->description('Bill of Material adalah daftar bahan dan jumlah yang digunakan untuk membuat sebuah produk.')
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_type')
                    ->label('Jenis Produk')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'semi-finished' => 'Setengah Jadi',
                        'finished' => 'Barang Jadi',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bomItems.material.name')
                    ->label('Bahan')
                    ->listWithLineBreaks()
                    ->searchable(),
                TextColumn::make('bom_quantities')
                    ->label('Jumlah')
                    ->getStateUsing(fn(Product $record): array => $record->bomItems
                        ->map(fn(BomItem $item): string => trim((float) $item->quantity . ' ' . $item->material?->unit))
                        ->all())
                    ->listWithLineBreaks(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(fn(Collection $records) => $records->each(
                            fn(Product $record) => $record->bomItems()->delete()
                        )),
                ]),
            ]);
    }
}
