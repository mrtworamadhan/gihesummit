<?php

namespace App\Filament\Resources\Payments\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Hitung total dari pembayaran yang sudah 'paid'
        // Asumsi di tabel payments atau registrations ada kolom currency/amount. 
        // Mari kita sesuaikan dengan struktur standar (misal kolom 'amount' dan 'currency' atau dipisah IDR/USD)
        
        $totalPaidIDR = Payment::where('payment_status', 'paid')
            ->where(function($q) {
                $q->where('currency', 'IDR')->orWhereNull('currency');
            })
            ->sum('final_amount');

        $totalPaidUSD = Payment::where('payment_status', 'paid')
            ->where('currency', 'USD')
            ->sum('final_amount');

        $countPaidTransactions = Payment::where('payment_status', 'paid')->count();

        return [
            Stat::make('Total Revenue (IDR)', 'Rp ' . number_format($totalPaidIDR, 0, ',', '.'))
                ->description('Total uang masuk dalam Rupiah')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Revenue (USD)', '$ ' . number_format($totalPaidUSD, 2, '.', ','))
                ->description('Total uang masuk dalam Dollar')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Successful Payments', $countPaidTransactions . ' Transaksi')
                ->description('Jumlah transaksi lunas')
                ->color('primary'),
        ];
    }
}
