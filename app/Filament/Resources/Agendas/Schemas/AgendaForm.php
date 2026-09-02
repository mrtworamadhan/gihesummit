<?php

namespace App\Filament\Resources\Agendas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AgendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Agenda')
                    ->placeholder('Misal: Serah Terima Kunci Kamar')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('Aturan Akses (Rule)')
                    ->options([
                        'general' => 'Bebas Akses (Semua Peserta)',
                        'check_in' => 'Check-in (Info Kamar akan muncul)',
                        'class' => 'Spesifik Kelas Tambahan',
                    ])
                    ->live()
                    ->required(),

                Select::make('additional_class_id')
                    ->label('Pilih Kelas (Wajib Akses)')
                    ->relationship('additionalClass', 'name')
                    ->visible(fn (Get $get) => $get('type') === 'class')
                    ->required(fn (Get $get) => $get('type') === 'class'),
                    
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}
