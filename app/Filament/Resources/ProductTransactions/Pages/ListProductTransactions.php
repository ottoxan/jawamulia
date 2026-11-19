<?php

namespace App\Filament\Resources\ProductTransactions\Pages;

use App\Filament\Resources\ProductTransactions\ProductTransactionResource;
use App\Models\ProductTransaction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProductTransactions extends ListRecords
{
    protected static string $resource = ProductTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Transaksi Produk'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(ProductTransaction::query()->count()),
            'semi-finished' => Tab::make('Setengah Jadi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('product', 'product_type', 'semi-finished'))
                ->badge(ProductTransaction::query()->whereRelation('product', 'product_type', 'semi-finished')->count()),
            'finished' => Tab::make('Barang Jadi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('product', 'product_type', 'finished'))
                ->badge(ProductTransaction::query()->whereRelation('product', 'product_type', 'finished')->count()),
        ];
    }
}
