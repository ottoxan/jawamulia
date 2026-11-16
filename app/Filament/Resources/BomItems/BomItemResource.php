<?php

namespace App\Filament\Resources\BomItems;

use App\Filament\Resources\BomItems\Pages\CreateBomItem;
use App\Filament\Resources\BomItems\Pages\EditBomItem;
use App\Filament\Resources\BomItems\Pages\ListBomItems;
use App\Filament\Resources\BomItems\Schemas\BomItemForm;
use App\Filament\Resources\BomItems\Tables\BomItemsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class BomItemResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $label = 'BOM Produk';

    protected static ?string $pluralLabel = 'BOM Produk';

    protected static string|UnitEnum|null $navigationGroup = 'Master data';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['bomItems.material'])
            ->whereHas('bomItems');
    }

    public static function form(Schema $schema): Schema
    {
        return BomItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BomItemsTable::configure($table);
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
            'index' => ListBomItems::route('/'),
            'create' => CreateBomItem::route('/create'),
            'edit' => EditBomItem::route('/{record}/edit'),
        ];
    }
}
