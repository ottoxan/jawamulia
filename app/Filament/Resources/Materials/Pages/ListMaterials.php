<?php

namespace App\Filament\Resources\Materials\Pages;

use App\Filament\Resources\Materials\MaterialResource;
use App\Models\Material;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Bahan'),
        ];
    }

    public function getTabs(): array
    {
        $user = auth()->user();

        if ($user?->hasRole('raw_admin') && ! $user->hasRole('super_admin')) {
            return [
                'baku' => Tab::make('Bahan Baku')
                    ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('material_type', 'baku'))
                    ->badge(Material::query()->where('material_type', 'baku')->count()),
            ];
        }

        return [
            'all' => Tab::make('Semua')
                ->badge(Material::query()->count()),
            'baku' => Tab::make('Bahan Baku')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('material_type', 'baku'))
                ->badge(Material::query()->where('material_type', 'baku')->count()),
            'penolong' => Tab::make('Bahan Penolong')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('material_type', 'penolong'))
                ->badge(Material::query()->where('material_type', 'penolong')->count()),
        ];
    }
}
