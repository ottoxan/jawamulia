<?php

namespace App\Filament\Resources\Employees\Schemas;

use App\Models\Employee;
use App\Models\User;
use App\Models\WorkingStation;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Group::make([
                    TextInput::make('name')
                        ->label('Nama Karyawan')
                        ->autocomplete('off')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->autocomplete('off')
                        ->required()
                        ->maxLength(255)
                        ->unique(
                            table: User::class,
                            column: 'email',
                            ignorable: fn(?Employee $record): ?User => $record?->user,
                        ),
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->autocomplete('new-password')
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->maxLength(255),
                    Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->native(false)
                        ->options([
                            'laki-laki' => 'Laki-laki',
                            'perempuan' => 'Perempuan',
                        ])
                        ->required(),
                ]),
                Group::make([
                    Select::make('role')
                        ->label('Role')
                        ->options(fn(): array => Role::query()
                            ->pluck('name', 'name')
                            ->map(fn(string $name): string => match ($name) {
                                'prod_admin' => 'Admin Produksi',
                                'raw_admin' => 'Admin Baku',
                                'aux_admin' => 'Admin Penolong',
                                'mkt_admin' => 'Admin Marketing',
                                'super_admin' => 'Admin Super',
                                default => $name,
                            })
                            ->all())
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('job')
                        ->label('Pekerjaan')
                        ->autocomplete('off')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('dob')
                        ->label('Tanggal Lahir')
                        ->native(false)
                        ->required(),
                    Select::make('station_id')
                        ->label('Meja Kerja')
                        ->relationship('workingStation', 'name')
                        ->getOptionLabelFromRecordUsing(fn(WorkingStation $record): string => "{$record->name} ({$record->job}) - {$record->employees()->count()}/{$record->max_employees}")
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->required()
                        ->rules([
                            fn(?Employee $record): Closure => function (string $attribute, $value, Closure $fail) use ($record): void {
                                $station = WorkingStation::find($value);

                                if (!$station) {
                                    return;
                                }

                                $currentCount = $station->employees()
                                    ->when($record, fn($query) => $query->whereKeyNot($record->getKey()))
                                    ->count();

                                if ($currentCount >= $station->max_employees) {
                                    $fail("Meja Kerja {$station->name} sudah penuh (maksimal {$station->max_employees} karyawan).");
                                }
                            },
                        ]),
                ]),
            ]);
    }
}
