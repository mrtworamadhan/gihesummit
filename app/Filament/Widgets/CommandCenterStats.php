<?php

namespace App\Filament\Widgets;

use App\Models\Participant;
use App\Models\Payment;
use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommandCenterStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPaid = Payment::where('payment_status', 'paid')->count();
        $revenueIdr = Payment::where('payment_status', 'paid')->where('currency', 'IDR')->sum('final_amount');
        
        $singleRoom = Registration::where('room_type_preference', 'Single')
            ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
            ->count();
            
        $twinRoom = Registration::where('room_type_preference', 'Twin')
            ->whereHas('participant.registration.payment', fn($q) => $q->where('payment_status', 'paid'))
            ->count();

        return [
            Stat::make('Total Pendaftar', Participant::count())
                ->description("{$totalPaid} Verified (Paid)")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Revenue (IDR)', 'Rp ' . number_format($revenueIdr, 0, ',', '.'))
                ->description('Total pembayaran terverifikasi')
                ->color('primary'),

            Stat::make('Kebutuhan Kamar', "{$singleRoom} Single")
                ->description(ceil($twinRoom / 2) . " Kamar Twin ({$twinRoom} Pax)")
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),
        ];
    }
}
