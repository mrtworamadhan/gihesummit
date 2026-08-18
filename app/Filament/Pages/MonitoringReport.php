<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CommandCenterStats;
use BackedEnum;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\Registration;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
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

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Peserta')
                ->badge(Registration::count()),
                
            'paid_no_room' => Tab::make('Paid & Belum Dapat Kamar')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                    ->whereNull('room_id') // Asumsi field room_id kosong jika belum di-assign
                )
                ->badgeColor('danger'),
                
            'paid_has_room' => Tab::make('Paid & Sudah Dapat Kamar')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
                    ->whereNotNull('room_id')
                )
                ->badgeColor('success'),
        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->state([])
            ->schema([
                Section::make('Rincian Pendaftar')
                    ->description('Analisis Demografi dan Status Verifikasi (Real-time)')
                    ->icon('heroicon-m-users')
                    ->schema([
                        // BARIS 1: Angka Tunggal (Poin 1 - 4)
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
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Registration::query())
            ->columns([
                TextColumn::make('participant.user.name')
                    ->label('Nama Peserta')
                    ->searchable(),
                    
                TextColumn::make('participant.institution_name')
                    ->label('Instansi')
                    ->searchable(),
                    
                TextColumn::make('room_type_preference')
                    ->label('Request Kamar')
                    ->badge(),
                    
                TextColumn::make('room.room_number')
                    ->label('Assigned Room')
                    ->default('Belum Diatur')
                    ->color(fn ($state) => $state === 'Belum Diatur' ? 'danger' : 'success'),
            ])
            ->filters([
                // Filter tambahan di dalam tabel jika dibutuhkan
                SelectFilter::make('room_type_preference')
                    ->label('Tipe Kamar')
                    ->options([
                        'Single' => 'Single Room',
                        'Twin' => 'Twin Room',
                    ]),
            ])
            ->headerActions([
                // Tempat meletakkan tombol Export Excel & PDF nanti
            ]);
    }

}
