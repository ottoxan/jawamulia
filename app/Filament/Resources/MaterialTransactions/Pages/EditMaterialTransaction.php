<?php

namespace App\Filament\Resources\MaterialTransactions\Pages;

use App\Filament\Resources\MaterialTransactions\MaterialTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaterialTransaction extends EditRecord
{
    protected static string $resource = MaterialTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
