<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Models\Employee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.roles.name')
                    ->label('Role')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'prod_admin' => 'Admin Produksi',
                        'raw_admin' => 'Admin Baku',
                        'aux_admin' => 'Admin Penolong',
                        'mkt_admin' => 'Admin Marketing',
                        'super_admin' => 'Admin Super',
                        default => $state,
                    })
                    ->badge(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                        default => $state,
                    })
                    ->badge(),
                TextColumn::make('dob')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('job')
                    ->label('Pekerjaan')
                    ->searchable(),
                TextColumn::make('workingStation.name')
                    ->label('Meja Kerja')
                    ->badge()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateRecordDataUsing(function (array $data, Employee $record): array {
                        $data['email'] = $record->user?->email;
                        $data['role'] = $record->user?->roles->first()?->name;

                        return $data;
                    })
                    ->using(function (Employee $record, array $data): Employee {
                        return DB::transaction(function () use ($record, $data): Employee {
                            if ($record->user) {
                                $userData = [
                                    'name' => $data['name'],
                                    'email' => $data['email'],
                                ];

                                if (filled($data['password'] ?? null)) {
                                    $userData['password'] = $data['password'];
                                }

                                $record->user->update($userData);

                                if (!empty($data['role'])) {
                                    $record->user->syncRoles([$data['role']]);
                                }
                            }

                            $record->update($data);

                            return $record;
                        });
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
