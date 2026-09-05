<?php

namespace App\Filament\Exports;

use App\Models\AgendaAttendance; // GANTI DENGAN NAMA MODEL PRESENSI KAMU
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class AgendaAttendanceExporter extends Exporter
{
    // 1. Pastikan modelnya mengarah ke tabel rekap scan kehadiran
    protected static ?string $model = AgendaAttendance::class; 

    public static function modifyQuery(Builder $query): Builder
    {
        return $query
            // FIX: Paksa query hanya mengambil kolom dari tabel presensi
            ->select($query->getModel()->getTable() . '.*')
            
            // Reorder wajib ditambahkan agar chunking aman
            ->reorder($query->getModel()->getTable() . '.' . $query->getModel()->getKeyName())
            
            ->with([
                'participant.user',
                'participant.registration.room',
                'agenda',
            ]);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')
                ->label('Waktu Scan')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d-m-Y H:i:s') : '-'), 

            ExportColumn::make('participant.uuid_barcode')
                ->label('UUID Barcode'),

            ExportColumn::make('participant.user.name')
                ->label('Nama Peserta'),

            ExportColumn::make('participant.user.institution_name')
                ->label('Instansi'),

            // --- TAMBAHKAN KOLOM NOMOR KAMAR DI SINI ---
            ExportColumn::make('participant.registration.room.room_number')
                ->label('Nomor Kamar')
                ->default('Belum Dialokasikan/Tanpa Kamar'), // Munculkan teks ini jika kosong

            ExportColumn::make('agenda.name') 
                ->label('Nama Agenda / Kelas'),

            ExportColumn::make('status')
                ->label('Status Kehadiran')
                ->default('Hadir'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data kehadiran agenda berhasil di-export ke Excel!';
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }
        return $body;
    }
}