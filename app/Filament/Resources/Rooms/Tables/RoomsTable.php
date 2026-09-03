<?php

namespace App\Filament\Resources\Rooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
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
                    ->label('Nomor Kamar')
                    ->searchable()
                    ->sortable()
                    ->visible(fn ($livewire) => ! (isset($livewire->isQuickEdit) && $livewire->isQuickEdit)),

                TextInputColumn::make('room_number_edit')
                    ->label('Nomor Kamar (Mode Edit)')
                    ->getStateUsing(fn ($record) => $record->room_number) 
                    ->updateStateUsing(fn ($record, $state) => $record->update(['room_number' => $state]))
                    ->visible(fn ($livewire) => isset($livewire->isQuickEdit) && $livewire->isQuickEdit),
                    
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
                    ->formatStateUsing(function ($state, $record) {
                        if ($state >= $record->capacity) {
                            return 'Full';
                        }
                        
                        return $state . ' / ' . $record->capacity;
                    })
                    ->badge()
                    ->color(function ($state, $record) {
                        if ($state >= $record->capacity) {
                            return 'danger'; 
                        }
                        
                        if ($state > 0) {
                            return 'warning'; 
                        }
                        
                        return 'success';
                    }),

                TextColumn::make('occupants_list') 
                    ->label('Occupants / Penghuni')
                    ->html()
                    ->getStateUsing(function ($record) { 
                        $occupants = $record->registrations()->with('participant.user')->get();
                        
                        if ($occupants->isEmpty()) {
                            return '<span class="text-gray-400 italic">Kamar Kosong</span>';
                        }

                        $listItems = $occupants->map(function ($reg) {
                            $name = $reg->participant?->user?->name ?? 'Unknown';
                            $inst = $reg->participant?->user?->institution_name;
                            $displayText = $inst ? "{$name} <span class='text-xs text-gray-500'>({$inst})</span>" : $name;
                            
                            return "<li>{$displayText}</li>";
                        })->implode('');

                        return "<ol class='list-decimal ml-4 space-y-1'>{$listItems}</ol>";
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->whereHas('registrations.participant.user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                            ->orWhere('institution_name', 'like', "%{$search}%");
                        });
                    }),
                    
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
