<?php

namespace App\Filament\Exports;

use App\Models\Participant; // Model yang benar
use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class ParticipantExporter extends Exporter
{
    // 1. UBAH KE PARTICIPANT
    protected static ?string $model = Participant::class; 

    public static function modifyQuery(Builder $query): Builder
    {
        // 2. LOAD RELASI BERSARANG (NESTED) SECARA EKSPLISIT
        return $query->with([
            'user',
            'registration.room',    // <-- Wajib agar room_number terbaca
            'registration.payment', // <-- Wajib agar payment_status terbaca
        ]);
    }

    public static function getColumns(): array
    {
        // MURNI HANYA NAMA KOLOM, TIDAK BOLEH ADA FUNCTION/CLOSURE DI SINI
        return [
            ExportColumn::make('user.name')->label('Nama Lengkap'),
            ExportColumn::make('user.email')->label('Email'),
            ExportColumn::make('user.gender')->label('Gender'),
            ExportColumn::make('user.whatsapp')->label('No. WhatsApp'),
            ExportColumn::make('user.nationality')->label('Kewarganegaraan'),
            ExportColumn::make('user.institution_name')->label('Instansi'),
            
            ExportColumn::make('type_of_institution')->label('Type Instansi'),
            ExportColumn::make('position_title')->label('Posisi/Jabatan'),
            
            ExportColumn::make('registration.room_type_preference')->label('Pilihan Kamar'),
            ExportColumn::make('registration.room.room_number')->label('Nomor Kamar'),
            ExportColumn::make('registration.payment.payment_status')->label('Status Bayar'),
            
            ExportColumn::make('registration.preferred_working_group')->label('Minat'),
            ExportColumn::make('registration.willingness_to_cosign_declaration')->label('Kesediaan TTD Deklarasi'),
            ExportColumn::make('registration.needs_accommodation_assist')->label('Asistensi Akomodasi'),
            ExportColumn::make('registration.accessibility_needs')->label('Aksebilitas'),
            ExportColumn::make('registration.requires_visa_letter')->label('Butuh Surat Visa'),               
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Master data excel berhasil di-generate!';
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diexport.';
        }
        return $body;
    }
}