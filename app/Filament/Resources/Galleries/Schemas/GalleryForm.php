<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Photo Documentation')
                    ->description('Upload foto kegiatan untuk ditampilkan di galeri peserta summit.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Photo Title / Description')
                            ->placeholder('Ex: Opening Ceremony GIHES 2026')
                            ->maxLength(255),
                            
                        FileUpload::make('image_path')
                            ->label('Photo Image')
                            ->image()
                            ->directory('galleries')
                            ->required()
                            ->columnSpanFull(),
                            
                        Toggle::make('is_published')
                            ->label('Publish to Participant Gallery?')
                            ->default(true)
                            ->helperText('Jika dimatikan, foto tidak akan muncul di dashboard peserta.'),
                    ]),
            ]);
    }
}
