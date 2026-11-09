<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('material_type')
                    ->native(false)
                    ->options([
                        'baku' => 'Bahan Baku',
                        'penolong' => 'Bahan Penolong',
                    ])
                    ->required(),
                TextInput::make('unit')
                    ->required(),
                Textarea::make('description'),
            ]);
    }
}
