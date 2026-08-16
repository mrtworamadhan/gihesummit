<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class InstitutionTypeStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = '2'; 

    protected function getStats(): array
    {
        // 1. Query Data Institusi (Murni tanpa gangguan tabel, jadi AMAN dari error)
        $institutions = Participant::query()
            ->select('type_of_institution', DB::raw('COUNT(*) as total'))
            ->whereNotNull('type_of_institution')
            ->where('type_of_institution', '!=', '')
            ->groupBy('type_of_institution')
            ->orderByDesc('total') // Urutkan dari yang terbanyak
            ->get();

        // Jadikan teks: "Pesantren (20) • Madrasah (15) • NGO (5)"
        $instText = $institutions->map(function ($item) {
            return $item->type_of_institution . ' (' . $item->total . ')';
        })->implode(' • ');

        // 2. Query Data Provinsi
        $provinces = Participant::query()
            ->select('province', DB::raw('COUNT(*) as total'))
            ->whereNotNull('province')
            ->where('province', '!=', '')
            ->groupBy('province')
            ->orderByDesc('total') // Urutkan dari yang terbanyak
            ->get();

        // Jadikan teks: "Jawa Barat (30) • Jawa Timur (10) • Banten (5)"
        $provText = $provinces->map(function ($item) {
            return $item->province . ' (' . $item->total . ')';
        })->implode(' • ');

        // Ambil juara 1 nya untuk ditampilkan besar di judul
        $topInst = $institutions->first();
        $topProv = $provinces->first();

        return [
            Stat::make('Demografi Institusi', $topInst ? "Terbanyak: {$topInst->type_of_institution}" : 'Belum ada data')
                ->description($instText ?: 'Belum ada pendaftar')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('info')
                ->columnSpan(3), // Lebar 2 kolom

            Stat::make('Demografi Provinsi', $topProv ? "Terbanyak: {$topProv->province}" : 'Belum ada data')
                ->description($provText ?: 'Belum ada pendaftar')
                ->descriptionIcon('heroicon-m-map')
                ->color('success')
                ->columnSpan(3), // Lebar 2 kolom
        ];
    }
}
