<?php

namespace App\Filament\Resources\Gatekeepers\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class GatekeepersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),
                TextColumn::make('magic_token')
                    ->label('Magic Link (Kirim via WA)')
                    ->formatStateUsing(fn ($state) => url('/gatekeeper/' . $state)) 
                    ->copyable()
                    ->copyMessage('Link berhasil di-copy!')
                    ->copyMessageDuration(1500)
                    ->color('primary')
                    ->icon('heroicon-o-clipboard-document-check'),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('send_wa')
                    ->label('Send Link')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation() // Muncul pop-up "Are you sure?"
                    ->modalHeading('Kirim Magic Link via WhatsApp')
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengirim akses Gatekeeper ke {$record->name} ({$record->whatsapp})?")
                    ->action(function ($record) {
                        
                        $url = url('/gatekeeper/' . $record->magic_token);
                        
                        // Pesan yang dikirim ke petugas
                        $pesan = "Halo *{$record->name}*,\n\n"
                               . "Anda telah ditugaskan sebagai Petugas/Gatekeeper GIHES 2026.\n\n"
                               . "Berikut adalah *Magic Link* Anda untuk mengakses alat Scanner Barcode:\n"
                               . "{$url}\n\n"
                               . "⚠️ _Mohon tidak membagikan link ini kepada siapapun._";

                        // Tembak ke API Fonnte
                        $response = Http::withHeaders([
                            'Authorization' => 'eYx7Pa6K2xiSE4s9aQxo'
                        ])->post('https://api.fonnte.com/send', [
                            'target' => $record->whatsapp,
                            'message' => $pesan,
                        ]);

                        if ($response->successful()) {
                            Notification::make()
                                ->title('WhatsApp berhasil dikirim!')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Gagal mengirim WhatsApp.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
