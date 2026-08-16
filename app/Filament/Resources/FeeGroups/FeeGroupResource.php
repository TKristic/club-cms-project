<?php

namespace App\Filament\Resources\FeeGroups;

use App\Filament\Resources\FeeGroups\Pages\CreateFeeGroup;
use App\Filament\Resources\FeeGroups\Pages\EditFeeGroup;
use App\Filament\Resources\FeeGroups\Pages\ListFeeGroups;
use App\Filament\Resources\FeeGroups\Schemas\FeeGroupForm;
use App\Filament\Resources\FeeGroups\Tables\FeeGroupsTable;
use App\Models\FeeGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeeGroupResource extends Resource
{
    protected static ?string $model = FeeGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Grupe članarina';
    protected static ?string $modelLabel = 'grupa članarina';
    protected static ?string $pluralModelLabel = 'Grupe članarina';

    public static function form(Schema $schema): Schema
    {
        return FeeGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeeGroupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeeGroups::route('/'),
            'create' => CreateFeeGroup::route('/create'),
            'edit' => EditFeeGroup::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlayersRelationManager::class,
        ];
    }
}
