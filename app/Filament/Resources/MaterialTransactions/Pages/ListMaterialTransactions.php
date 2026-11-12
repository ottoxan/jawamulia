<?php

namespace App\Filament\Resources\MaterialTransactions\Pages;

use App\Filament\Resources\MaterialTransactions\MaterialTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterialTransactions extends ListRecords
{
    protected static string $resource = MaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
