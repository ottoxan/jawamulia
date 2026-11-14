<?php

namespace App\Filament\Resources\WorkingStations\Schemas;

use App\Models\WorkingStation;
use Closure;
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
                    ->required()
                    ->rules([
                        fn (?WorkingStation $record): Closure => function (string $attribute, $value, Closure $fail) use ($record): void {
                            if (! $record) {
                                return;
                            }

                            $currentCount = $record->employees()->count();

                            if ((int) $value < $currentCount) {
                                $fail("Maksimal karyawan tidak boleh kurang dari jumlah karyawan saat ini ({$currentCount} karyawan).");
                            }
                        },
                    ]),
            ]);
    }
}
