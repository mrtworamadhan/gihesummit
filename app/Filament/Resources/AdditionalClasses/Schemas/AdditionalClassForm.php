<?php

namespace App\Filament\Resources\AdditionalClasses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdditionalClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Additional Class Details')
                    ->description('Atur nama kelas tambahan dan kuota peserta yang bisa mendaftar.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Class Name')
                            ->placeholder('Ex: AI & EdTech Masterclass')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('speaker')
                            ->label('Instructor / Speaker')
                            ->placeholder('Ex: Dr. Ahmad')
                            ->maxLength(255),

                        TextInput::make('price_idr')
                            ->label('Price (IDR)')
                            ->placeholder('Ex: 500000')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('price_usd')
                            ->label('Price (USD)')
                            ->placeholder('Ex: 30')
                            ->numeric()
                            ->prefix('USD')
                            ->required(),
                            
                        TextInput::make('quota')
                            ->label('Participant Quota')
                            ->numeric()
                            ->default(50)
                            ->required(),
                            
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
