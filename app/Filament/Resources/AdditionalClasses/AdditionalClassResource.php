<?php

namespace App\Filament\Resources\AdditionalClasses;

use App\Filament\Resources\AdditionalClasses\Pages\CreateAdditionalClass;
use App\Filament\Resources\AdditionalClasses\Pages\EditAdditionalClass;
use App\Filament\Resources\AdditionalClasses\Pages\ListAdditionalClasses;
use App\Filament\Resources\AdditionalClasses\RelationManagers\RegistrationsRelationManager;
use App\Filament\Resources\AdditionalClasses\Schemas\AdditionalClassForm;
use App\Filament\Resources\AdditionalClasses\Tables\AdditionalClassesTable;
use App\Models\AdditionalClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdditionalClassResource extends Resource
{
    protected static ?string $model = AdditionalClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string | UnitEnum | null $navigationGroup = 'Event Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdditionalClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdditionalClassesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RegistrationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdditionalClasses::route('/'),
            'create' => CreateAdditionalClass::route('/create'),
            'edit' => EditAdditionalClass::route('/{record}/edit'),
        ];
    }
}
