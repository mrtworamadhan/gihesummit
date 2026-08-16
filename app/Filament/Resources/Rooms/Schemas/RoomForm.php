<?php

namespace App\Filament\Resources\Rooms\Schemas;

use App\Models\Room;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Room Configuration')
                    ->description('Kelola nomor kamar, jenis, dan kapasitas penghuni.')
                    ->schema([
                        TextInput::make('room_number')
                            ->label('Room Number / Name')
                            ->placeholder('Ex: Room 301 - VIP Wing')
                            ->required()
                            ->maxLength(255),
                            
                        Select::make('type')
                            ->label('Room Type')
                            ->options([
                                'Single' => 'Single Room',
                                'Twin' => 'Twin Sharing (2 Beds)',
                            ])
                            ->required(),
                            
                        TextInput::make('capacity')
                            ->label('Max Capacity (Persons)')
                            ->numeric()
                            ->default(2)
                            ->required(),
                            
                        Toggle::make('is_available')
                            ->label('Available for Assignment?')
                            ->default(true)
                            ->onColor('success'),
                    ])->columns(2),

                Section::make('Assigned Delegates (Paid Only)')
                    ->description('Pilih dan tetapkan peserta yang sudah lunas ke kamar ini. (Pastikan jumlahnya tidak melebihi kapasitas kamar).')
                    ->icon('heroicon-o-users')
                    ->hiddenOn('create')
                    ->schema([
                        Select::make('registrations')
                            ->label('Select Delegates for This Room')
                            ->relationship(
                                name: 'registrations',
                                titleAttribute: 'id',
                                modifyQueryUsing: fn ($query) => $query->whereHas('payment', fn ($q) => $q->where('payment_status', 'paid'))
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->participant->user->name} ({$record->participant->user->institution_name})")
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->reactive() // Agar bisa mendeteksi jumlah yang dipilih secara real-time
                            ->afterStateUpdated(function ($state, Set $set, ?Room $record) {
                                if ($record && count($state) > $record->capacity) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Peringatan Kapasitas!')
                                        ->body("Jumlah peserta yang dipilih (" . count($state) . ") melebihi kapasitas kamar ini (" . $record->capacity . ").")
                                        ->danger()
                                        ->send();
                                }
                            })
                            ->helperText('Pilih maksimal sesuai kapasitas kamar (misal: 2 orang untuk Twin Sharing).'),
                    ]),
            ]);
    }
}
