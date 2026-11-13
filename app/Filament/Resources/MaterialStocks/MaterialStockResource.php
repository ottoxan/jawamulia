<?php

namespace App\Filament\Resources\MaterialStocks;

use App\Filament\Resources\MaterialStocks\Pages\CreateMaterialStock;
use App\Filament\Resources\MaterialStocks\Pages\EditMaterialStock;
use App\Filament\Resources\MaterialStocks\Pages\ListMaterialStocks;
use App\Filament\Resources\MaterialStocks\Schemas\MaterialStockForm;
use App\Filament\Resources\MaterialStocks\Tables\MaterialStocksTable;
use App\Models\MaterialStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MaterialStockResource extends Resource
{
    protected static ?string $model = MaterialStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static string|UnitEnum|null $navigationGroup = 'Gudang';

    protected static ?string $label = 'Stok Bahan';

    protected static ?string $pluralLabel = 'Stok Bahan';

    public static function form(Schema $schema): Schema
    {
        return MaterialStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialStocksTable::configure($table);
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
            'index' => ListMaterialStocks::route('/'),
            // 'create' => CreateMaterialStock::route('/create'),
            // 'edit' => EditMaterialStock::route('/{record}/edit'),
        ];
    }
}
