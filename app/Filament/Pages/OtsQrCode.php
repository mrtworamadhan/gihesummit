<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Http;
use UnitEnum;

class OtsQrCode extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;
    protected static ?string $navigationLabel = 'OTS QR Code';
    protected static ?string $title = 'GIHES 2026 - OTS Registration';
    protected static string | UnitEnum | null $navigationGroup = 'Transactions & Delegates';

    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.pages.ots-qr-code';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Download QR')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(function () {
                    // Ambil URL QR Code
                    $url = 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode(route('ots.register'));
                    
                    // Tarik gambar pakai HTTP client Laravel
                    $image = Http::get($url)->body();

                    // Paksa browser untuk download sebagai file .png
                    return response()->streamDownload(function () use ($image) {
                        echo $image;
                    }, 'QR-OTS-GIHES-2026.png', [
                        'Content-Type' => 'image/png',
                    ]);
                }),

            Action::make('open_link')
                ->label('Buka Form OTS')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('info')
                ->url(fn (): string => route('ots.register'))
                ->openUrlInNewTab(),

            Action::make('print')
                ->label('Cetak Standee')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->extraAttributes(['onclick' => 'window.print()']),
        ];
    }

    // 2. Bangun Tampilan Menggunakan Infolist API
    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            // Karena ini custom page tanpa model/database, kita beri state data manual
            ->state([
                'title' => 'SCAN DI SINI UNTUK REGISTRASI OTS',
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode(route('ots.register')),
                'price' => 'Rp 2.000.000',
                'description' => 'Tiket Tanpa Akomodasi',
            ])
            ->schema([
                Section::make('Standee QR Code')
                    ->description('Cetak halaman ini dan letakkan di meja registrasi.')
                    ->schema([
                        Grid::make(1)->schema([
                            TextEntry::make('title')
                                ->hiddenLabel()
                                ->size(TextSize::Large)
                                ->weight('black')
                                ->alignCenter(),
                            
                            ImageEntry::make('qr_url')
                                ->hiddenLabel()
                                ->width(300)
                                ->height(300)
                                ->extraImgAttributes(['class' => 'mx-auto rounded-xl p-2 border-4 border-gray-200 shadow-sm my-4'])
                                ->alignCenter(),

                            TextEntry::make('price')
                                ->hiddenLabel()
                                ->size(TextSize::Large)
                                ->weight('bold')
                                ->color('success')
                                ->alignCenter(),
                                
                            TextEntry::make('description')
                                ->hiddenLabel()
                                ->color('gray')
                                ->alignCenter(),
                        ]),
                    ])
                    ->extraAttributes(['class' => 'print-area']) // Kelas khusus untuk CSS Print
            ]);
    }
    
}
