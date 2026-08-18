<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Support\Facades\Http;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration.participant.user.name')
                    ->label('Participant Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('currency')
                    ->badge() 
                    ->color(fn (string $state): string => match ($state) {
                        'IDR' => 'warning',
                        'USD' => 'success',
                        default => 'gray',
                    }),
                    
                TextColumn::make('final_amount')
                    ->numeric() 
                    ->sortable(),

                ImageColumn::make('payment_proof_path')
                    ->label('Proof')
                    ->square(),
                TextColumn::make('verifier.name')
                    ->label('Verified By')
                    ->badge()
                    ->color('gray')
                    ->default('Belum diverifikasi')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                
                Action::make('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn (Payment $record) => $record->payment_status === 'paid')
                    ->action(function (Payment $record) {
                        $registration = $record->registration;
                        
                        $sisaKuota = Registration::getRemainingQuota();
                        
                        if ($sisaKuota > 0) {
                            $registration->update(['is_waiting_list' => false]);
                            $statusPesan = "You are confirmed as a Main Delegate.";
                        } else {
                            $registration->update(['is_waiting_list' => true]);
                            $statusPesan = "Main quota is full, you are placed on the priority Waiting List.";
                        }

                        $record->update(['payment_status' => 'paid', 'verified_by' => auth()->id(),]);
                        
                        if ($registration && $registration->room_id) {
                            $registration->room->checkAndLockAvailability();
                        }

                        $user = $registration->participant->user;
                        $noWa = $user->whatsapp;
                        
                        if (str_starts_with($noWa, '0')) {
                            $noWa = '62' . substr($noWa, 1);
                        }

                        $pesanWA = "Dear {$user->name},\n\nYour payment for GIHES 2026 has been *SUCCESSFULLY* confirmed.\n\nStatus: {$statusPesan}\n\nPlease log in to your dashboard to view your E-Pass.\n\nBest regards,\nGIHES 2026 Committee";

                        try {
                            Http::withHeaders([
                                'Authorization' => 'eYx7Pa6K2xiSE4s9aQxo',
                            ])->post('https://api.fonnte.com/send', [
                                'target' => $noWa,
                                'message' => $pesanWA,
                            ]);

                            Notification::make()
                                ->title('Payment Approved & WA Sent!')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Approved, but failed to send WA.')
                                ->warning()
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
