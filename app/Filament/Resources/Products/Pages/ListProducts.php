<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\Product;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Produk'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Product::query()->count()),
            'semi-finished' => Tab::make('Setengah Jadi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('product_type', 'semi-finished'))
                ->badge(Product::query()->where('product_type', 'semi-finished')->count()),
            'finished' => Tab::make('Barang Jadi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('product_type', 'finished'))
                ->badge(Product::query()->where('product_type', 'finished')->count()),
        ];
    }
}
