<?php

namespace App\Filament\Resources\WorkingStations\Tables;

use App\Models\WorkingStation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkingStationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('job')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('max_employees')
                    ->label('Max Employees')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employees_count')
                    ->counts('employees')
                    ->label('Employees')
                    ->formatStateUsing(fn ($state, WorkingStation $record): string => "{$state} / {$record->max_employees}")
                    ->badge()
                    ->color(fn ($state, WorkingStation $record): string => $state >= $record->max_employees ? 'danger' : 'success'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
