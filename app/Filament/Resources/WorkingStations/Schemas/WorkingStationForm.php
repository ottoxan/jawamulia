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
                TextInput::make('name')
                    ->label('Nama Meja')
                    ->required()
                    ->maxLength(255),
                TextInput::make('job')
                    ->label('Pekerjaan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('max_employees')
                    ->label('Maksimal Karyawan')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }
}
