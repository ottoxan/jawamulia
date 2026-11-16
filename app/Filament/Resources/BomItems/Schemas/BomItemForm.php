<?php

namespace App\Filament\Resources\BomItems\Schemas;

use App\Models\Material;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BomItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Produk')
                    ->options(fn (): array => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->native(false)
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                    ->visibleOn('create'),
                TextInput::make('name')
                    ->label('Produk')
                    ->disabled()
                    ->dehydrated(false)
                    ->columnSpanFull()
                    ->visibleOn('edit'),
                Repeater::make('items')
                    ->label('Daftar Bahan')
                    ->addActionLabel('Tambah Bahan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->minItems(1)
                    ->defaultItems(1)
                    ->reorderable(false)
                    ->visibleOn('create')
                    ->schema(static::bomItemSchema()),
                Repeater::make('bomItems')
                    ->label('Daftar Bahan')
                    ->relationship('bomItems')
                    ->addActionLabel('Tambah Bahan')
                    ->columns(2)
                    ->columnSpanFull()
                    ->minItems(1)
                    ->reorderable(false)
                    ->visibleOn('edit')
                    ->schema(static::bomItemSchema()),
            ]);
    }

    /**
     * @return array<int, Component>
     */
    private static function bomItemSchema(): array
    {
        return [
            Select::make('material_id')
                ->label('Bahan')
                ->options(fn (): array => Material::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->native(false)
                ->preload()
                ->live()
                ->distinct()
                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                ->required(),
            TextInput::make('quantity')
                ->label('Jumlah')
                ->numeric()
                ->step('any')
                ->gt(0)
                ->required()
                ->suffix(fn (Get $get): ?string => Material::find($get('material_id'))?->unit),
        ];
    }
}
