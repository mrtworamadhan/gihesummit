<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalRegistration = Registration::count();
        
        $totalPaid = Payment::where('payment_status', 'paid')->count();

        $totalWni = \App\Models\Participant::whereHas('user', function ($q) {
            $q->where('nationality', 'Indonesia');
        })->count();

        $totalWna = \App\Models\Participant::whereHas('user', function ($q) {
            $q->where('nationality', '!=', 'Indonesia');
        })->count();

        return [
            Stat::make('Total Registrasi', $totalRegistration)
                ->description('Jumlah keseluruhan pendaftar summit')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Sudah Pembayaran (Paid)', $totalPaid)
                ->description('Transaksi lunas terverifikasi')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Nasional vs Internasional', "WNI: {$totalWni} | WNA: {$totalWna}")
                ->description('Perbandingan delegasi lokal & luar negeri')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),
        ];
    }
}
