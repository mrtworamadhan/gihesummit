<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Core Account Data')
                    ->description('Informasi login dan identitas utama (Otomatis update ke tabel Users).')
                    ->icon('heroicon-o-identification')
                    ->relationship('user')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('whatsapp')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                            
                        TextInput::make('nationality')
                            ->required()
                            ->maxLength(100),
                            
                        TextInput::make('institution_name')
                            ->label('Institution Name (User Table)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Institutional Detail Profile')
                    ->description('Detail spesifik tentang jabatan dan skala institusi.')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        TextInput::make('position_title')
                            ->label('Position / Title')
                            ->maxLength(255),
                            
                        Select::make('type_of_institution')
                            ->label('Type of Institution')
                            ->options([
                                'Pesantren' => 'Pesantren',
                                'Islamic School (Madrasah)' => 'Islamic School (Madrasah)',
                                'University/Higher Education' => 'University/Higher Education',
                                'Government/Ministry' => 'Government/Ministry',
                                'NGO/Foundation' => 'NGO/Foundation',
                                'Corporate/EdTech' => 'Corporate/EdTech',
                                'Other' => 'Other',
                            ]),
                            
                        Select::make('institution_scale')
                            ->label('Institution Scale')
                            ->options([
                                'Local' => 'Local (City/Province)',
                                'National' => 'National',
                                'International' => 'International',
                            ]),
                            
                        TextInput::make('province')
                            ->label('Province / State')
                            ->maxLength(255),
                            
                        TextInput::make('website_social_media')
                            ->label('Website / Social Media URL')
                            ->url()
                            ->maxLength(255),
                            
                        Textarea::make('institution_address')
                            ->label('Full Address')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),
            ]);
    }
}
