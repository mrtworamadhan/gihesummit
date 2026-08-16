<?php

namespace App\Filament\Resources\Registrations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant.user.name')
                    ->label('Delegate Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('participant.user.institution_name')
                    ->label('Institution')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('participant.user.position_title')
                    ->label('Position')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('role_at_summit')
                    ->label('Role')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('room.room_number')
                    ->label('Room')
                    ->placeholder('Not Selected')
                    ->sortable(),

                IconColumn::make('is_waiting_list')
                    ->label('Waitlist')
                    ->boolean()
                    ->trueIcon('heroicon-o-clock')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('payment.payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'danger',
                    })
                    ->default('Incomplete'),
            ])
            ->filters([
                // Filter dropdown tidak terlalu butuh karena kita sudah pakai Tabs di atas
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    
                ]),
            ]);
    }
}
