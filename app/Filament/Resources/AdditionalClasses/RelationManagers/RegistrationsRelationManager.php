<?php

namespace App\Filament\Resources\AdditionalClasses\RelationManagers;

use App\Filament\Exports\ClassParticipantExporter;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    protected static ?string $title = 'Daftar Peserta Kelas';

    public function form(Schema $form): Schema
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('payment', function ($q) {
                $q->where('payment_status', 'paid');
            }))
            ->columns([
                TextColumn::make('participant.user.name')
                    ->label('Nama Peserta')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('participant.user.whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-phone'),

                TextColumn::make('participant.user.institution_name')
                    ->label('Instansi / Lembaga')
                    ->searchable()
                    ->wrap(),
                    
                TextColumn::make('room_type_preference')
                    ->label('Tipe Kamar')
                    ->badge()
                    ->color('info')
                    ->toggleable(isToggledHiddenByDefault: true),
        
            ])
            ->filters([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ClassParticipantExporter::class)
                    ->label('Export Data Kelas')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down'),
                AttachAction::make()
                    ->label('Tambahkan Peserta')
                    ->icon('heroicon-o-user-plus')
                    ->color('primary')
                    ->multiple()
                    ->preloadRecordSelect()
                    ->recordSelect(
                        fn (Select $select) => $select->getOptionLabelFromRecordUsing(
                            fn ($record) => ($record->participant?->user?->name ?? 'Unknown') . ' - ' . 
                                            ($record->participant?->institution_name ?? 'No Institution')
                        )
                    )
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Keluarkan')
                    ->icon('heroicon-o-x-mark'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->label('Keluarkan Terpilih'),
                ]),
            ]);
    }
}
