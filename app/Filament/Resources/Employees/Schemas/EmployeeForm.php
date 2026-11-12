<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('gender')
                    ->native(false)
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ->required(),
                DatePicker::make('dob')
                    ->label('DOB')
                    ->native(false)
                    ->required(),
                TextInput::make('job')
                    ->required()
                    ->maxLength(255),
                Select::make('station_id')
                    ->relationship('workingStation', 'station_name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
