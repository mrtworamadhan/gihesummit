<?php

namespace App\Filament\Resources\Gatekeepers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GatekeeperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Petugas')
                    ->required()
                    ->maxLength(255),
                TextInput::make('whatsapp')
                    ->label('Nomor WhatsApp')
                    ->placeholder('Contoh: 08123456789')
                    ->required()
                    ->maxLength(20),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}
