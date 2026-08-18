<?php

namespace App\Filament\Resources\Registrations\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdditionalClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'additionalClasses';

    protected static ?string $title = 'Assigned Classes & Tours';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $form): Schema
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kelas / Tour')
                    ->weight('bold')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('day')
                    ->label('Hari')
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('time')
                    ->label('Jam')
                    ->time('H:i'),
                    
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Assign Class')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->preloadRecordSelect() 
                    ->multiple(), 
            ])
            ->actions([
                DetachAction::make()
                    ->label('Remove')
                    ->icon('heroicon-o-x-mark'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}