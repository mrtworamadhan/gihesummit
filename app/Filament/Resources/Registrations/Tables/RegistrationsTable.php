<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Jobs\SendEidWhatsAppJob;
use App\Models\Room;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant.user.name')
                    ->label('Delegate Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                TextColumn::make('participant.user.institution_name')
                    ->label('Institution')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('participant.position_title')
                    ->label('Position')
                    ->searchable()
                    ->limit(30),

                 TextColumn::make('participant.user.gender')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'info',    
                        'Female' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('room_type_preference')
                    ->label('Tipe Kamar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Single' => 'warning', 
                        'Twin' => 'success',  
                        default => 'gray',  
                    })
                    ->sortable(),

                SelectColumn::make('room_id')
                    ->label('Assign Room')
                    ->options(function ($record) {
                        if ($record && $record->room_type_preference) {
                            return Room::where('type', $record->room_type_preference)
                                ->where('is_available', true)
                                ->pluck('room_number', 'id');
                        }
                        
                        return Room::where('is_available', true)->pluck('room_number', 'id');
                    })
                    ->disabled(fn ($record) => $record->payment?->payment_status !== 'paid')
                    ->sortable(),

                IconColumn::make('is_waiting_list')
                    ->label('Waitlist')
                    ->boolean()
                    ->trueIcon('heroicon-o-clock')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                TextColumn::make('payment.payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'danger',
                    })
                    ->default('Incomplete'),
            ])
            ->filters([
                // Filter dropdown tidak terlalu butuh karena kita sudah pakai Tabs di atas
            ])
            ->recordActions([
                Action::make('send_wa_eid')
                    ->label('Kirim E-Pass')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    
                    // CEK LUNAS: Langsung ke relasi payment karena ini Registration model
                    ->visible(fn ($record) => $record->payment?->payment_status === 'paid')
                    
                    ->requiresConfirmation()
                    ->modalHeading('Kirim E-Pass via WhatsApp')
                    ->modalDescription('Kirimkan link Digital ID Card ke nomor WA peserta ini?')
                    ->action(function ($record) {
                        SendEidWhatsAppJob::dispatchSync($record->participant);
                    })
                    ->successNotificationTitle('Pesan WA berhasil dikirim!'),
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('blast_wa_eid')
                        ->label('Blast E-Pass Masal')
                        ->icon('heroicon-o-megaphone')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Blast E-Pass via WhatsApp')
                        ->modalDescription('PENTING: Sistem secara otomatis HANYA akan mengirim WA kepada peserta yang status pembayarannya sudah PAID. Peserta Pending akan diabaikan.')
                        ->action(function (Collection $records) {
                            
                            $validRecords = $records->filter(function ($record) {
                                return $record->payment?->payment_status === 'paid';
                            });

                            if ($validRecords->isEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Proses Dibatalkan')
                                    ->body('Dari data yang Anda pilih, belum ada peserta dengan status pembayaran PAID.')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            $delayInSeconds = 0;
                            
                            foreach ($validRecords as $record) {
                                SendEidWhatsAppJob::dispatch($record->participant)->delay(now()->addSeconds($delayInSeconds));
                                $delayInSeconds += 10;
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Blasting Berjalan!')
                                ->body("Berhasil memasukkan {$validRecords->count()} peserta LUNAS ke dalam antrean WA.")
                                ->success()
                                ->send();

                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
