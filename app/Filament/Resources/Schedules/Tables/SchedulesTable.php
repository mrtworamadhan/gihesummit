<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day')
                    ->label('Day')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'info',
                        '2' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => "Day {$state}")
                    ->sortable(),
                    
                TextColumn::make('time_range')
                    ->label('Time')
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('session_name')
                    ->label('Session')
                    ->searchable()
                    ->wrap(), 
                    
                TextColumn::make('speaker')
                    ->label('Speaker')
                    ->searchable()
                    ->wrap(),
                    
                IconColumn::make('is_break')
                    ->label('Break')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle') 
                    ->falseIcon('heroicon-o-minus'),
            ])
            ->filters([
                SelectFilter::make('day')
                    ->options([
                        1 => 'Day 1',
                        2 => 'Day 2',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('day', 'asc') 
            ->defaultSort('id', 'asc');
    }
}
