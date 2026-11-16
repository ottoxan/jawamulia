<?php

namespace App\Filament\Resources\BomItems\Pages;

use App\Filament\Resources\BomItems\BomItemResource;
use App\Models\Product;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBomItems extends ListRecords
{
    protected static string $resource = BomItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Product::query()->whereHas('bomItems')->count()),
            'semi-finished' => Tab::make('Setengah Jadi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('product_type', 'semi-finished'))
                ->badge(Product::query()->whereHas('bomItems')->where('product_type', 'semi-finished')->count()),
            'finished' => Tab::make('Barang Jadi')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('product_type', 'finished'))
                ->badge(Product::query()->whereHas('bomItems')->where('product_type', 'finished')->count()),
        ];
    }
}
