<?php

namespace App\Filament\Resources\Registrations\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delegate Profile Summary')
                    ->description('Data profil peserta (Read-Only). Untuk mengubah nama atau institusi, silakan edit melalui menu Participants.')
                    ->icon('heroicon-o-user-circle')
                    ->hiddenOn('create') // Kita sembunyikan saat admin "Create" manual agar tidak error
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->user?->name ?? '-'),
                            
                        TextInput::make('email')
                            ->label('Email Address')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->user?->email ?? '-'),
                            
                        TextInput::make('whatsapp')
                            ->label('WhatsApp Number')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->user?->whatsapp ?? '-'),
                        
                        TextInput::make('position_title')
                            ->label('Position Title')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->position_title ?? '-'),
                        
                    ])->columns(2),

                Section::make('Institution Profil')
                    ->description('Data Institusi (Read-Only). Untuk mengubah nama atau institusi, silakan edit melalui menu Participants.')
                    ->icon('heroicon-o-building-library')
                    ->hiddenOn('create') 
                    ->schema([
                        TextInput::make('institution_name')
                            ->label('Institution Name')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->user?->institution_name ?? '-'),

                        TextInput::make('type_of_institution')
                            ->label('Type of Institution')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->type_of_institution ?? '-'),
                            
                        TextInput::make('institution_address')
                            ->label('Institution Address')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->institution_address ?? '-'),
                            
                        TextInput::make('province')
                            ->label('Province/State')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->province ?? '-'),
                            
                        TextInput::make('institution_scale')
                            ->label('Institution Scale')
                            ->disabled()
                            ->placeholder(fn ($record) => $record?->participant?->institution_scale ?? '-'),
                    ])->columns(2),

                Section::make('Summit Participation & Mandate')
                    ->description('Peran peserta, working group, dan dokumen surat mandat.')
                    ->icon('heroicon-o-star')
                    ->schema([
                        Select::make('role_at_summit')
                            ->options([
                                'Participant' => 'Participant',
                                'Speaker' => 'Speaker',
                                'Committee' => 'Committee',
                                'VIP' => 'VIP',
                            ])
                            ->required(),
                            
                        TextInput::make('showcase_category')
                            ->label('Showcase Category')
                            ->placeholder('Ex: EdTech, Governance, dll')
                            ->maxLength(255),

                        Select::make('preferred_working_group')
                            ->label('Preferred Working Groups')
                            ->multiple()
                            ->options([
                                'Curriculum Design' => 'Curriculum Design',
                                'Institutional Management' => 'Institutional Management',
                                'Digital Transformation & AI' => 'Digital Transformation & AI',
                                'Character Building & Mental Health' => 'Character Building & Mental Health',
                                'Global Network & MoU' => 'Global Network & MoU',
                            ]),

                        FileUpload::make('mandate_letter_path')
                            ->label('Mandate / Assignment Letter')
                            ->disk('public')
                            ->directory('mandate_letters')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->openable()
                            ->downloadable(),
                            
                        Toggle::make('willingness_to_cosign_declaration')
                            ->label('Willing to Co-sign Declaration?'),
                            
                        Toggle::make('is_waiting_list')
                            ->label('Status: Masuk Waiting List?')
                            ->onColor('warning')
                            ->offColor('success'),
                    ])->columns(2),

                Section::make('Programs & Additional Classes')
                    ->description('Kelas tambahan yang diambil oleh peserta (Otomatis dari relasi Pivot).')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        CheckboxList::make('additionalClasses')
                            ->label('Enrolled Classes')
                            ->relationship(name: 'additionalClasses', titleAttribute: 'name')
                            ->columns(2)
                            ->gridDirection('row')
                            ->helperText('Centang kelas yang akan diikuti oleh peserta ini.'),
                    ]),

                Section::make('Accommodation & Room Assignment')
                    ->description('Admin dapat menempatkan (assign) atau memindahkan kamar peserta di sini.')
                    ->icon('heroicon-o-building-office-2')
                    ->collapsed() 
                    ->schema([
                        TextInput::make('room_type_preference')
                            ->label('Tipe Kamar (Pilihan Peserta)')
                            ->disabled(),
                            
                        Select::make('room_id')
                            ->label('Assign Room Number')
                            ->relationship(
                                name: 'room', 
                                titleAttribute: 'room_number',
                                modifyQueryUsing: fn (Builder $query, Get $get) => $query
                                    ->where('type', $get('room_type_preference'))
                                    ->where('is_available', true) 
                            )
                            ->searchable()
                            ->preload()
                            ->helperText('Opsi kamar otomatis menyesuaikan tipe pilihan peserta di atas.'),
                            
                        Toggle::make('needs_accommodation_assist')
                            ->label('Needs Accommodation Assistance?'),
                    ])->columns(3),

                
                Section::make('Logistics & Travel Itinerary')
                    ->description('Jadwal penerbangan dan kebutuhan khusus diet/aksesibilitas.')
                    ->icon('heroicon-o-paper-airplane')
                    ->collapsed() 
                    ->schema([
                        TextInput::make('departure_city_country')
                            ->label('Departure City/Country'),
                            
                        DateTimePicker::make('estimated_arrival')
                            ->label('Estimated Arrival'),
                            
                        DateTimePicker::make('estimated_departure')
                            ->label('Estimated Departure'),
                            
                        TextInput::make('dietary_restrictions')
                            ->label('Dietary Restrictions (Alergi/Halal)'),
                            
                        TextInput::make('accessibility_needs')
                            ->label('Accessibility Needs (Kursi Roda dll)'),
                    ])->columns(2),
            ]);
    }
}
