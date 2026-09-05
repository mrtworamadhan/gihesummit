<?php

namespace App\Filament\Exports;

use App\Models\Registration;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ClassParticipantExporter extends Exporter
{
    protected static ?string $model = Registration::class;

    public static function modifyQuery(Builder $query): Builder
    {
        return $query
            // FIX: Paksa query hanya mengambil kolom dari tabel registrations 
            // agar kolom 'id' tidak bentrok dengan tabel relasi/pivot
            ->select($query->getModel()->getTable() . '.*')
            
            // Reorder wajib ditambahkan agar chunking Laravel tidak bingung
            ->reorder($query->getModel()->getTable() . '.' . $query->getModel()->getKeyName())
            
            ->with([
                'participant.user',
                'payment',
                'room'
            ]);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('participant.user.name')->label('Nama Peserta'),
            ExportColumn::make('participant.user.whatsapp')->label('WhatsApp'),
            ExportColumn::make('participant.user.institution_name')->label('Instansi / Lembaga'),
            ExportColumn::make('participant.user.gender')->label('Gender'),
            ExportColumn::make('room.room_number')->label('Nomor Kamar')->default('Tanpa Kamar'),
            ExportColumn::make('payment.payment_status')->label('Status Pembayaran'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data peserta kelas berhasil diexport!';
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }
        return $body;
    }
}