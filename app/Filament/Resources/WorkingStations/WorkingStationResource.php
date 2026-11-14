<?php

namespace App\Filament\Resources\WorkingStations;

use App\Filament\Resources\WorkingStations\Pages\CreateWorkingStation;
use App\Filament\Resources\WorkingStations\Pages\EditWorkingStation;
use App\Filament\Resources\WorkingStations\Pages\ListWorkingStations;
use App\Filament\Resources\WorkingStations\Schemas\WorkingStationForm;
use App\Filament\Resources\WorkingStations\Tables\WorkingStationsTable;
use App\Models\WorkingStation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class WorkingStationResource extends Resource
{
    protected static ?string $model = WorkingStation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Meja Kerja';

    protected static ?string $pluralLabel = 'Meja Kerja';

    protected static string|UnitEnum|null $navigationGroup = 'Master data';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return WorkingStationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkingStationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkingStations::route('/'),
            // 'create' => CreateWorkingStation::route('/create'),
            // 'edit' => EditWorkingStation::route('/{record}/edit'),
        ];
    }
}
