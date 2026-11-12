<?php

namespace App\Filament\Resources\WorkingStations\Pages;

use App\Filament\Resources\WorkingStations\WorkingStationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkingStations extends ListRecords
{
    protected static string $resource = WorkingStationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
