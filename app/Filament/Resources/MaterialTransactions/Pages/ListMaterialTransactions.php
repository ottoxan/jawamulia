<?php

namespace App\Filament\Resources\MaterialTransactions\Pages;

use App\Filament\Resources\MaterialTransactions\MaterialTransactionResource;
use App\Models\MaterialTransaction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMaterialTransactions extends ListRecords
{
    protected static string $resource = MaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        if ($user?->hasRole('raw_admin') && ! $user->hasRole('super_admin')) {
            return [
                'baku' => Tab::make('Bahan Baku')
                    ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('material', 'material_type', 'baku'))
                    ->badge(MaterialTransaction::query()->whereRelation('material', 'material_type', 'baku')->count()),
            ];
        }

        return [
            'all' => Tab::make('Semua')
                ->badge(MaterialTransaction::query()->count()),
            'baku' => Tab::make('Bahan Baku')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('material', 'material_type', 'baku'))
                ->badge(MaterialTransaction::query()->whereRelation('material', 'material_type', 'baku')->count()),
            'penolong' => Tab::make('Bahan Penolong')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereRelation('material', 'material_type', 'penolong'))
                ->badge(MaterialTransaction::query()->whereRelation('material', 'material_type', 'penolong')->count()),
        ];
    }
}
