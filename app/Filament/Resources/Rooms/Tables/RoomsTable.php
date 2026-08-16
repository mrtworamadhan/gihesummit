<?php

namespace App\Filament\Resources\Rooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room_number')
                    ->label('Room Number')
                    ->searchable()
                    ->weight('bold'),
                    
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Single' => 'warning', 
                        'Twin' => 'info', 
                        default => 'gray',
                    }),
                    
                TextColumn::make('capacity')
                    ->label('Capacity'),
                    
                TextColumn::make('registrations_count')
                    ->label('Occupied')
                    ->counts('registrations')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('registrations')
                    ->label('Occupants / Penghuni')
                    ->formatStateUsing(function ($record) {
                        // Ambil semua peserta di kamar ini
                        $occupants = $record->registrations()->with('participant.user')->get();
                        
                        if ($occupants->isEmpty()) {
                            return 'Kamar Kosong';
                        }

                        // Gabungkan nama-nama penghuni dengan koma atau baris baru
                        return $occupants->map(function ($reg) {
                            $name = $reg->participant?->user?->name ?? 'Unknown';
                            $inst = $reg->participant?->user?->institution_name;
                            return $inst ? "{$name} ({$inst})" : $name;
                        })->implode(', ');
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        // Agar tetap bisa dicari berdasarkan nama user atau institusi
                        return $query->whereHas('registrations.participant.user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('institution_name', 'like', "%{$search}%");
                        });
                    })
                    ->wrap(),
                    
                IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_available')
                    ->label('Availability'),
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
