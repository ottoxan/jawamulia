<?php

namespace App\Filament\Resources\MaterialStocks\Pages;

use App\Filament\Resources\MaterialStocks\MaterialStockResource;
use App\Models\MaterialStock;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMaterialStocks extends ListRecords
{
    protected static string $resource = MaterialStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        if ($user?->hasRole('raw_admin') && ! $user->hasRole('super_admin')) {
            return [
                'baku' => Tab::make('Bahan Baku')
                    ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('material', 'material_type', 'baku'))
                    ->badge(MaterialStock::query()->whereRelation('material', 'material_type', 'baku')->count()),
            ];
        }

        return [
            'all' => Tab::make('Semua')
                ->badge(MaterialStock::query()->count()),
            'baku' => Tab::make('Bahan Baku')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('material', 'material_type', 'baku'))
                ->badge(MaterialStock::query()->whereRelation('material', 'material_type', 'baku')->count()),
            'penolong' => Tab::make('Bahan Penolong')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('material', 'material_type', 'penolong'))
                ->badge(MaterialStock::query()->whereRelation('material', 'material_type', 'penolong')->count()),
        ];
    }
}
