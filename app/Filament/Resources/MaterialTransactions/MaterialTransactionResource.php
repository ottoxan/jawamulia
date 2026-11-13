<?php

namespace App\Filament\Resources\MaterialTransactions;

use App\Filament\Resources\MaterialTransactions\Pages\CreateMaterialTransaction;
use App\Filament\Resources\MaterialTransactions\Pages\EditMaterialTransaction;
use App\Filament\Resources\MaterialTransactions\Pages\ListMaterialTransactions;
use App\Filament\Resources\MaterialTransactions\Schemas\MaterialTransactionForm;
use App\Filament\Resources\MaterialTransactions\Tables\MaterialTransactionsTable;
use App\Models\MaterialTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MaterialTransactionResource extends Resource
{
    protected static ?string $model = MaterialTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsUpDown;

    protected static ?string $recordTitleAttribute = 'MaterialTransaction';

    protected static ?string $label = 'Transaksi Bahan';

    protected static ?string $pluralLabel = 'Transaksi Bahan';

    protected static string|UnitEnum|null $navigationGroup = 'Gudang';

    public static function form(Schema $schema): Schema
    {
        return MaterialTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialTransactionsTable::configure($table);
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
            'index' => ListMaterialTransactions::route('/'),
            // 'create' => CreateMaterialTransaction::route('/create'),
            // 'edit' => EditMaterialTransaction::route('/{record}/edit'),
        ];
    }
}
