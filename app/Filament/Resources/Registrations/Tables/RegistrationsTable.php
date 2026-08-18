<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Models\Room;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
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

                TextColumn::make('participant.position_title')
                    ->label('Position')
                    ->searchable()
                    ->limit(30),

                 TextColumn::make('participant.user.gender')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'info',    
                        'Female' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('room_type_preference')
                    ->label('Tipe Kamar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Single' => 'warning', 
                        'Twin' => 'success',  
                        default => 'gray',  
                    })
                    ->sortable(),

                SelectColumn::make('room_id')
                    ->label('Assign Room')
                    ->options(function ($record) {
                        if ($record && $record->room_type_preference) {
                            return Room::where('type', $record->room_type_preference)
                                ->where('is_available', true)
                                ->pluck('room_number', 'id');
                        }
                        
                        return Room::where('is_available', true)->pluck('room_number', 'id');
                    })
                    ->disabled(fn ($record) => $record->payment?->payment_status !== 'paid')
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
