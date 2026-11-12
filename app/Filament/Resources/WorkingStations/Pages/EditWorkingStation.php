<?php

namespace App\Filament\Resources\WorkingStations\Pages;

use App\Filament\Resources\WorkingStations\WorkingStationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkingStation extends EditRecord
{
    protected static string $resource = WorkingStationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
