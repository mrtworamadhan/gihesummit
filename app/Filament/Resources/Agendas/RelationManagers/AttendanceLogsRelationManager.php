<?php

namespace App\Filament\Resources\Agendas\RelationManagers;

use App\Filament\Exports\AgendaAttendanceExporter;
use App\Filament\Resources\Agendas\AgendaResource;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ExportAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendanceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'attendanceLogs';

    protected static ?string $relatedResource = AgendaResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('registration_id')
            ->heading('Log Kehadiran Peserta')
            ->description('Daftar peserta yang telah melakukan scan barcode di agenda ini.')
            ->columns([
                TextColumn::make('registration.participant.user.name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('registration.user.institution_name')
                    ->label('Instansi/Asal')
                    ->searchable()
                    ->limit(20),

                TextColumn::make('gatekeeper.name')
                    ->label('Petugas Scan')
                    ->icon('heroicon-o-user-circle')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y - H:i:s')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'force_accepted' => 'danger',
                        'rejected' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'success' => 'Terkonfirmasi',
                        'force_accepted' => 'Bypass (Force Accept)',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->size('xs')
                    ->color('gray')
                    ->wrap(),
            ])
            ->filters([
                // Filter berdasarkan status
                SelectFilter::make('status')
                    ->options([
                        'success' => 'Terkonfirmasi',
                        'force_accepted' => 'Bypass (Force Accept)',
                    ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(AgendaAttendanceExporter::class)
                    ->label('Export Kehadiran')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down'),
            ])
            ->actions([
                // Kita berikan fitur hapus, jaga-jaga kalau petugas salah scan
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
