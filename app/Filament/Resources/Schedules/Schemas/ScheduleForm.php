<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Schedule Details')
                    ->description('Atur sesi, waktu, dan pembicara untuk agenda GIHES 2026.')
                    ->schema([
                        Select::make('day')
                            ->label('Day')
                            ->options([
                                1 => 'Day 1 (Hari Pertama)',
                                2 => 'Day 2 (Hari Kedua)',
                            ])
                            ->required(),
                            
                        TextInput::make('time_range')
                            ->label('Time Range')
                            ->placeholder('Ex: 08.00 - 09.00')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('session_name')
                            ->label('Session Name')
                            ->placeholder('Ex: Keynote I / Plenary Session')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('speaker')
                            ->label('Speaker / PIC')
                            ->placeholder('Ex: Prof. Hamid Fahmy Zarkasyi')
                            ->maxLength(255),
                            
                        Textarea::make('topic_description')
                            ->label('Topic / Description')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Toggle::make('is_break')
                            ->label('Is this a Break Session?')
                            ->helperText('Aktifkan jika ini adalah waktu istirahat (Coffee/Lunch break) agar warnanya beda di panel peserta.')
                            ->onColor('warning'),
                    ])->columns(2),
            ]);
    }
}
