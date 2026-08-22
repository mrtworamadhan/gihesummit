<?php

namespace App\Filament\Pages;

use App\Filament\Exports\ParticipantExporter;
use App\Filament\Widgets\CommandCenterStats;
use BackedEnum;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Room;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MonitoringReport extends Page implements HasTable, HasInfolists
{
    use InteractsWithTable, InteractsWithInfolists;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;
    protected static ?string $navigationLabel = 'Command Center';
    protected static ?string $title = 'GIHES 2026 - Monitoring & Reports';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.monitoring-report';


    protected function getHeaderWidgets(): array
    {
        return [
            CommandCenterStats::class,
        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->state([])
            ->schema([
                // Ini sintaks persis seperti yang kamu maksud bro!
                Tabs::make('Laporan Terpadu')
                    ->tabs([
                        
                        // TAB 1: DEMOGRAFI
                        Tab::make('Demografi Peserta')
                            ->icon('heroicon-m-users')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('total_pendaftar')
                                            ->label('1. Total Terdaftar')
                                            ->default(fn () => Participant::count())
                                            ->badge()
                                            ->color('gray'),

                                        TextEntry::make('total_terverifikasi')
                                            ->label('2. Terverifikasi (Paid)')
                                            ->default(fn () => Payment::where('payment_status', 'paid')->count())
                                            ->badge()
                                            ->color('success'),

                                        TextEntry::make('total_wni')
                                            ->label('3. Total WNI')
                                            // Sesuaikan nama kolom negara di tabelmu (asumsi: 'country')
                                            ->default(fn () => Participant::whereHas('user', function ($q) {
                                                    $q->where('nationality', 'Indonesia');
                                                })->count())
                                            ->badge()
                                            ->color('info'),

                                        TextEntry::make('total_wna')
                                            ->label('4. Total WNA')
                                            ->default(fn () => Participant::whereHas('user', function ($q) {
                                                    $q->where('nationality', '!=', 'Indonesia');
                                                })->count())
                                            ->badge()
                                            ->color('warning'),
                                    ]),

                                // BARIS 2: Data Grouping / Breakdown (Poin 5 - 7)
                                Grid::make(3)
                                    ->schema([
                                        KeyValueEntry::make('by_institution')
                                            ->label('5. Per Tipe Institusi')
                                            ->keyLabel('Tipe')
                                            ->valueLabel('Jumlah')
                                            ->default(fn () => Participant::query()
                                                // COALESCE memastikan kalau datanya kosong, tetap tampil rapi bukan error null
                                                ->selectRaw("COALESCE(type_of_institution, 'Belum Diisi') as kategori, count(*) as total")
                                                ->groupBy('kategori')
                                                ->pluck('total', 'kategori')
                                                ->toArray()
                                            ),

                                        KeyValueEntry::make('by_province')
                                            ->label('6. Per Provinsi/State')
                                            ->keyLabel('Wilayah')
                                            ->valueLabel('Jumlah')
                                            ->default(fn () => Participant::query()
                                                ->selectRaw("COALESCE(province, 'Belum Diisi') as kategori, count(*) as total")
                                                ->groupBy('kategori')
                                                ->pluck('total', 'kategori')
                                                ->toArray()
                                            ),

                                        KeyValueEntry::make('by_position')
                                            ->label('7. Per Posisi (Jabatan)')
                                            ->keyLabel('Jabatan')
                                            ->valueLabel('Jumlah')
                                            ->default(fn () => Participant::query()
                                                ->selectRaw("COALESCE(position_title, 'Belum Diisi') as kategori, count(*) as total")
                                                ->groupBy('kategori')
                                                ->pluck('total', 'kategori')
                                                ->toArray()
                                            ),
                                    ])
                            ]),
                            
                        Tab::make('Akomodasi')
                            ->icon('heroicon-m-building-office-2')
                            ->schema([
                                Section::make('Kebutuhan Akomodasi (Summary)')
                                    ->description('Kalkulasi kebutuhan kamar hotel berdasarkan pilihan peserta terverifikasi.')
                                    ->icon('heroicon-m-building-office-2')
                                    ->schema([
                                        Grid::make(4)->schema([
                                            TextEntry::make('req_single_verified')
                                                ->label('Kebutuhan Kamar Single')
                                                ->default(function () { 
                                                    $verifiedSingle = Registration::where('room_type_preference', 'Single')
                                                        ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                                                        ->count(); 
                                                    return "{$verifiedSingle} Peserta = {$verifiedSingle} Kamar"; 
                                                })
                                                ->badge()->color('warning'),

                                            TextEntry::make('avail_single')
                                                ->label('Status Kamar Single Tersedia')
                                                ->default(function () {
                                                    $needed = Registration::where('room_type_preference', 'Single')
                                                        ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                                                        ->count();
                                                    $available = Room::where('type', 'Single')->count(); // Sesuaikan kolom/model kamarmu
                                                    
                                                    $diff = $available - $needed;
                                                    $status = $diff < 0 ? "(Kurang " . abs($diff) . ")" : ($diff == 0 ? "(Pas)" : "(Sisa {$diff})");
                                                    
                                                    return "{$available} Kamar {$status}";
                                                })
                                                ->badge()
                                                ->color(fn ($state) => str_contains($state, 'Kurang') ? 'danger' : 'success'),

                                            TextEntry::make('req_twin_verified')
                                                ->label('Kebutuhan Kamar Twin')
                                                ->default(function () {
                                                    $verifiedTwin = Registration::where('room_type_preference', 'Twin')
                                                        ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                                                        ->count();
                                                    $neededKamar = ceil($verifiedTwin / 2);
                                                    return "{$verifiedTwin} Peserta = {$neededKamar} Kamar"; 
                                                })
                                                ->badge()->color('info'),

                                            TextEntry::make('avail_twin')
                                                ->label('Status Kamar Twin Tersedia')
                                                ->default(function () {
                                                    $verifiedTwin = Registration::where('room_type_preference', 'Twin')
                                                        ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                                                        ->count();
                                                    $needed = ceil($verifiedTwin / 2);
                                                    $available = Room::where('type', 'Twin')->count(); // Sesuaikan kolom/model kamarmu
                                                    
                                                    $diff = $available - $needed;
                                                    $status = $diff < 0 ? "(Kurang " . abs($diff) . ")" : ($diff == 0 ? "(Pas)" : "(Sisa {$diff})");
                                                    
                                                    return "{$available} Kamar {$status}";
                                                })
                                                ->badge()
                                                ->color(fn ($state) => str_contains($state, 'Kurang') ? 'danger' : 'success'),
                                        ]),
                                    ]),
                                Tabs::make('Daftar Peserta Kamar')
                                    ->tabs([
                                        
                                        Tab::make('Peserta Kamar Single')
                                            ->icon('heroicon-m-user')
                                            ->schema([
                                                RepeatableEntry::make('list_single')
                                                    ->label('')
                                                    ->default(function () {
                                                        return Registration::with(['participant.user','room'])
                                                            ->where('room_type_preference', 'Single')
                                                            ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                                                            ->get()
                                                            ->sortBy('room.room_number')
                                                            ->values()
                                                            ->toArray();
                                                    })
                                                    ->table([
                                                        TableColumn::make('Name'),
                                                        TableColumn::make('Gender'),
                                                        TableColumn::make('Institution'),
                                                        TableColumn::make('Position'),
                                                        TableColumn::make('Room Number'),
                                                    ])
                                                    ->schema([
                                                        TextEntry::make('participant.user.name')->label('Nama')->weight('bold'),
                                                        TextEntry::make('participant.user.gender')->label('Gender')->badge(),
                                                        TextEntry::make('participant.user.institution_name')->label('Institution')->wrap(),
                                                        TextEntry::make('participant.position_title')->label('Position')->wrap(),
                                                        TextEntry::make('room.room_number')->label('Room Number')->badge(),
                                                    ])->columns(3)
                                            ]),

                                        Tab::make('Peserta Kamar Twin')
                                            ->icon('heroicon-m-users')
                                            ->schema([
                                                RepeatableEntry::make('list_twin')
                                                    ->label('')
                                                    ->default(function () {
                                                        return Registration::with(['participant.user','room'])
                                                            ->where('room_type_preference', 'Twin')
                                                            ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                                                            ->get()
                                                            ->sortBy('room.room_number')
                                                            ->values()
                                                            ->toArray();
                                                    })
                                                    ->table([
                                                        TableColumn::make('Name'),
                                                        TableColumn::make('Gender'),
                                                        TableColumn::make('Institution'),
                                                        TableColumn::make('Position'),
                                                        TableColumn::make('Room Number'),
                                                    ])
                                                    ->schema([
                                                        TextEntry::make('participant.user.name')->label('Nama')->weight('bold'),
                                                        TextEntry::make('participant.user.gender')->label('Gender')->badge(),
                                                        TextEntry::make('participant.user.institution_name')->label('Institution')->wrap(),
                                                        TextEntry::make('participant.position_title')->label('Position')->wrap(),
                                                        TextEntry::make('room.room_number')->label('Room Number')->badge(),
                                                    ])->columns(3)
                                            ]),
                                    ])
                                    ->columnSpanFull()
                                    ->contained(false),
                            ]),
                        
                        Tab::make('Logistik')
                            ->icon('heroicon-m-clipboard-document-check')
                            ->schema([
                
                            Tabs::make('Kebutuhan Khusus')
                                ->tabs([
                                    
                                    // --- TAB 1: DIETARY RESTRICTION ---
                                    Tab::make('Dietary Restrictions')
                                        ->icon('heroicon-m-cake')
                                        ->badge(Registration::whereNotNull('dietary_restrictions')->count()) // Opsional: Kasih angka badge
                                        ->schema([
                                            RepeatableEntry::make('dietary_data') // NAMA HARUS UNIK
                                                ->label('Daftar Peserta dengan Pantangan Makanan')
                                                ->default(function () {
                                                    return Registration::with(['participant.user'])
                                                        ->whereNotNull('dietary_restrictions') // Filter khusus dietary
                                                        ->get()
                                                        ->toArray(); 
                                                })
                                                ->table([
                                                    TableColumn::make('Name'),
                                                    TableColumn::make('Gender'),
                                                    TableColumn::make('Dietary Restrictions'),
                                                ])
                                                ->schema([
                                                    TextEntry::make('participant.user.name')
                                                        ->label('Nama Peserta')
                                                        ->weight('bold'),
                                                        
                                                    TextEntry::make('participant.user.gender')
                                                        ->label('Gender')
                                                        ->badge()
                                                        ->color(fn ($state) => match($state) { 'Male' => 'info', 'Female' => 'danger', default => 'gray' }),
                                                        
                                                    TextEntry::make('dietary_restrictions')
                                                        ->label('Detail Dietary')
                                                        ->color('warning')
                                                        ->wrap(),
                                                ])
                                                ->columns(3),
                                        ]),

                                    // --- TAB 2: ACCESSIBILITY NEEDS ---
                                    Tab::make('Accessibility Needs')
                                        ->icon('heroicon-m-viewfinder-circle')
                                        ->badge(Registration::whereNotNull('accessibility_needs')->count())
                                        ->schema([
                                            RepeatableEntry::make('accessibility_data') // NAMA HARUS UNIK
                                                ->label('Daftar Peserta dengan Kebutuhan Aksesibilitas')
                                                ->default(function () {
                                                    return Registration::with(['participant.user'])
                                                        ->whereNotNull('accessibility_needs') // Filter khusus aksesibilitas
                                                        ->get()
                                                        ->toArray(); 
                                                })
                                                ->table([
                                                    TableColumn::make('Name'),
                                                    TableColumn::make('Gender'),
                                                    TableColumn::make('Accessibility Needs'),
                                                ])
                                                ->schema([
                                                    TextEntry::make('participant.user.name')
                                                        ->label('Nama Peserta')
                                                        ->weight('bold'),
                                                        
                                                    TextEntry::make('participant.user.gender')
                                                        ->label('Gender')
                                                        ->badge()
                                                        ->color(fn ($state) => match($state) { 'Male' => 'info', 'Female' => 'danger', default => 'gray' }),
                                                        
                                                    TextEntry::make('accessibility_needs')
                                                        ->label('Detail Aksesibilitas')
                                                        ->color('info')
                                                        ->wrap(),
                                                ])
                                                ->columns(3),
                                        ]),

                                    // --- TAB 3: ASISTENSI AKOMODASI ---
                                    Tab::make('Asistensi Akomodasi')
                                        ->icon('heroicon-m-lifebuoy')
                                        ->badge(Registration::where('needs_accommodation_assist', 1)->count())
                                        ->schema([
                                            RepeatableEntry::make('assist_data') // NAMA HARUS UNIK
                                                ->label('Daftar Peserta Butuh Pendampingan')
                                                ->default(function () {
                                                    return Registration::with(['participant.user'])
                                                        ->where('needs_accommodation_assist', 1) // Filter boolean asistensi
                                                        ->get()
                                                        ->toArray(); 
                                                })
                                                ->table([
                                                    TableColumn::make('Name'),
                                                    TableColumn::make('Gender'),
                                                    TableColumn::make('Needs Assistances'),
                                                ])
                                                ->schema([
                                                    TextEntry::make('participant.user.name')
                                                        ->label('Nama Peserta')
                                                        ->weight('bold'),
                                                        
                                                    TextEntry::make('participant.user.gender')
                                                        ->label('Gender')
                                                        ->badge()
                                                        ->color(fn ($state) => match($state) { 'Male' => 'info', 'Female' => 'danger', default => 'gray' }),
                                                        
                                                    IconEntry::make('needs_accommodation_assist')
                                                        ->label('Status Asistensi')
                                                        ->boolean(),
                                                ])
                                                ->columns(3),
                                        ]),

                                    Tab::make('Visa Letter')
                                        ->icon('heroicon-m-document-text')
                                        ->badge(Registration::where('requires_visa_letter', 1)->count())
                                        ->schema([
                                            RepeatableEntry::make('assist_data') // NAMA HARUS UNIK
                                                ->label('Daftar Peserta Butuh Visa Letter')
                                                ->default(function () {
                                                    return Registration::with(['participant.user'])
                                                        ->where('requires_visa_letter', 1) // Filter boolean visa letter
                                                        ->get()
                                                        ->toArray(); 
                                                })
                                                ->table([
                                                    TableColumn::make('Name'),
                                                    TableColumn::make('Gender'),
                                                    TableColumn::make('Requires Visa Letter'),
                                                ])
                                                ->schema([
                                                    TextEntry::make('participant.user.name')
                                                        ->label('Nama Peserta')
                                                        ->weight('bold'),
                                                        
                                                    TextEntry::make('participant.user.gender')
                                                        ->label('Gender')
                                                        ->badge()
                                                        ->color(fn ($state) => match($state) { 'Male' => 'info', 'Female' => 'danger', default => 'gray' }),
                                                        
                                                    IconEntry::make('requires_visa_letter')
                                                        ->label('Surat Visa')
                                                        ->boolean(),
                                                ])
                                                ->columns(3),
                                        ]),
                                        
                                ])->columnSpanFull(),
                            ])
                            
                    ])
                    ->columnSpanFull() 
            ]);
    }

}
