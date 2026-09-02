<?php

namespace App\Filament\Resources\Agendas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe Akses')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'success',
                        'check_in' => 'warning',
                        'class' => 'primary',
                    }),
                TextColumn::make('additionalClass.name')
                    ->label('Khusus Kelas')
                    ->placeholder('-'),
                IconColumn::make('is_active')
                    ->boolean(),
                
            ])
            ->filters([
                //
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
