<?php

namespace App\Filament\Resources\Gatekeepers;

use App\Filament\Resources\Gatekeepers\Pages\CreateGatekeeper;
use App\Filament\Resources\Gatekeepers\Pages\EditGatekeeper;
use App\Filament\Resources\Gatekeepers\Pages\ListGatekeepers;
use App\Filament\Resources\Gatekeepers\Schemas\GatekeeperForm;
use App\Filament\Resources\Gatekeepers\Tables\GatekeepersTable;
use App\Models\Gatekeeper;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GatekeeperResource extends Resource
{
    protected static ?string $model = Gatekeeper::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static string | UnitEnum | null $navigationGroup = 'Attendance Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GatekeeperForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GatekeepersTable::configure($table);
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
            'index' => ListGatekeepers::route('/'),
            'create' => CreateGatekeeper::route('/create'),
            'edit' => EditGatekeeper::route('/{record}/edit'),
        ];
    }
}
