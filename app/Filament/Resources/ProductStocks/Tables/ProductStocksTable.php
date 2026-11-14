<?php

namespace App\Filament\Resources\ProductStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ProductStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup(
                Group::make('product.product_type')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(fn ($record): string => match ($record->product?->product_type) {
                        'semi-finished' => 'Setengah Jadi',
                        'finished' => 'Barang Jadi',
                        default => (string) ($record->product?->product_type ?? '-'),
                    })
            )
            ->columns([
                TextColumn::make('Id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->rowIndex(),
                TextColumn::make('product.code')
                    ->label('Kode Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.product_type')
                    ->label('Jenis Produk')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'semi-finished' => 'Setengah Jadi',
                        'finished' => 'Barang Jadi',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock_type')
                    ->label('Tipe Stok')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'good' => 'Bagus (Good)',
                        'rejected' => 'Rusak (Rejected)',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'good' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state, $record): string => "{$state} {$record->product?->unit}")
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
