<?php

namespace App\Filament\Resources\WorkingStations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkingStationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('station_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
