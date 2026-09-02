<?php

namespace App\Filament\Resources\Agendas;

use App\Filament\Resources\Agendas\Pages\CreateAgenda;
use App\Filament\Resources\Agendas\Pages\EditAgenda;
use App\Filament\Resources\Agendas\Pages\ListAgendas;
use App\Filament\Resources\Agendas\RelationManagers\AttendanceLogsRelationManager;
use App\Filament\Resources\Agendas\Schemas\AgendaForm;
use App\Filament\Resources\Agendas\Tables\AgendasTable;
use App\Models\Agenda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AgendaResource extends Resource
{
    protected static ?string $model = Agenda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string | UnitEnum | null $navigationGroup = 'Attendance Management';

    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AgendaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgendasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AttendanceLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgendas::route('/'),
            'create' => CreateAgenda::route('/create'),
            'edit' => EditAgenda::route('/{record}/edit'),
        ];
    }
}
