<?php

namespace App\Filament\Resources\AdditionalClasses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdditionalClassesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Class Name')
                    ->searchable()
                    ->weight('bold'),
                    
                TextColumn::make('speaker')
                    ->label('Instructor')
                    ->searchable(),
                
                TextColumn::make('price_idr')
                    ->label('Price (IDR)')
                    ->money('idr', true)
                    ->sortable(),
                TextColumn::make('price_usd')
                    ->label('Price (USD)')
                    ->money('usd', true)
                    ->sortable(),
                    
                TextColumn::make('quota')
                    ->label('Quota')
                    ->badge()
                    ->color('info'),
                    
                TextColumn::make('registrations_count')
                    ->label('Enrolled')
                    ->counts('registrations') // Menghitung jumlah peserta yang ambil kelas ini
                    ->badge()
                    ->color('success'),
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
