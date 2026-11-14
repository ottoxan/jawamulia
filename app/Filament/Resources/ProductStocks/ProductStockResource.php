<?php

namespace App\Filament\Resources\ProductStocks;

use App\Filament\Resources\ProductStocks\Pages\CreateProductStock;
use App\Filament\Resources\ProductStocks\Pages\EditProductStock;
use App\Filament\Resources\ProductStocks\Pages\ListProductStocks;
use App\Filament\Resources\ProductStocks\Schemas\ProductStockForm;
use App\Filament\Resources\ProductStocks\Tables\ProductStocksTable;
use App\Models\ProductStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductStockResource extends Resource
{
    protected static ?string $model = ProductStock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static string|UnitEnum|null $navigationGroup = 'Gudang';

    protected static ?string $label = 'Stok Produk';

    protected static ?string $pluralLabel = 'Stok Produk';

    public static function form(Schema $schema): Schema
    {
        return ProductStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductStocksTable::configure($table);
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
            'index' => ListProductStocks::route('/'),
            // 'create' => CreateProductStock::route('/create'),
            // 'edit' => EditProductStock::route('/{record}/edit'),
        ];
    }
}
