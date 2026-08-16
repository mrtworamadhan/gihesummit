<?php

namespace App\Filament\Resources\Participants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('user.whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('WhatsApp number copied!'),

                TextColumn::make('position_title')
                    ->label('Position')
                    ->searchable(),
                    
                TextColumn::make('user.institution_name')
                    ->label('Institution')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('province')
                    ->label('Province/State')
                    ->searchable()
                    ->limit(30),
                    
                    
                TextColumn::make('user.nationality')
                    ->label('Nationality')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                //
            ])
            ->groups([
                Group::make('type_of_institution')
                    ->label('Tipe Institusi')
                    ->collapsible(), // Bisa di-collapse/buka-tutup
                    
                Group::make('province')
                    ->label('Provinsi')
                    ->collapsible(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
