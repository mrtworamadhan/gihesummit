<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('E-Certificate Template Setup')
                    ->description('Upload template sertifikat kosong. Sistem akan otomatis menuliskan nama peserta saat di-download.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Certificate Name')
                            ->placeholder('Ex: Official Summit Certificate / AI Workshop Certificate')
                            ->required()
                            ->maxLength(255),
                            
                        Select::make('type')
                            ->label('Certificate Type')
                            ->options([
                                'main' => 'Main Summit Certificate (Untuk semua peserta lunas)',
                                'class' => 'Special Class Certificate (Khusus kelas tambahan tertentu)',
                            ])
                            ->required()
                            ->reactive(), // Agar field di bawahnya bisa merespons perubahan tipe

                        Select::make('additional_class_id')
                            ->label('Target Additional Class')
                            ->relationship('additionalClass', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get) => $get('type') === 'class')
                            ->required(fn (Get $get) => $get('type') === 'class'),

                        FileUpload::make('template_path')
                            ->label('Blank Certificate Template (Image/PNG/JPG)')
                            ->image()
                            ->directory('certificate_templates')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Gunakan ukuran resolusi tinggi (landscape) agar hasil cetak nama tajam.'),
                            
                        Toggle::make('is_published')
                            ->label('Publish Certificates?')
                            ->helperText('Aktifkan jika acara sudah selesai dan peserta diizinkan mendownload sertifikat.')
                            ->onColor('success'),
                    ])->columns(2),
            ]);
    }
}
