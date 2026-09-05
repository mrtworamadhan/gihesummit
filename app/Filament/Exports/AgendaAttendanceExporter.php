<?php

namespace App\Filament\Exports;

use App\Models\AttendanceLog; // Pastikan ini model AttendanceLog kamu
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class AgendaAttendanceExporter extends Exporter
{
    protected static ?string $model = AttendanceLog::class; 

    public static function modifyQuery(Builder $query): Builder
    {
        return $query
            ->select($query->getModel()->getTable() . '.*')
            ->reorder($query->getModel()->getTable() . '.' . $query->getModel()->getKeyName())
            ->with([
                // JALUR BARU: Mulai dari registration -> participant -> user
                'registration.participant.user',
                'registration.room', // Ke kamar langsung dari registration
                'agenda',
            ]);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')
                ->label('Waktu Scan')
                ->formatStateUsing(fn ($state) => $state ? $state->format('d-m-Y H:i:s') : '-'), 

            // UBAH PREFIXNYA MENJADI registration.participant...
            ExportColumn::make('registration.participant.uuid_barcode')
                ->label('UUID Barcode'),

            ExportColumn::make('registration.participant.user.name')
                ->label('Nama Peserta'),
                
            ExportColumn::make('registration.participant.user.whatsapp')
                ->label('No. WhatsApp'),

            ExportColumn::make('registration.participant.user.institution_name')
                ->label('Instansi'),

            ExportColumn::make('registration.participant.type_of_institution')
                ->label('Tipe Instansi'),

            // KAMAR DIAMBIL DARI REGISTRATION
            ExportColumn::make('registration.room.room_number')
                ->label('Nomor Kamar')
                ->default('Belum Dialokasikan/Tanpa Kamar'), 

            ExportColumn::make('agenda.name') 
                ->label('Nama Agenda / Kelas'),

            ExportColumn::make('status')
                ->label('Status Kehadiran')
                ->default('Hadir'),

            ExportColumn::make('gatekeeper.name')
                ->label('Petugas Gatekeeper')
                ->default('Tidak Diketahui'),
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