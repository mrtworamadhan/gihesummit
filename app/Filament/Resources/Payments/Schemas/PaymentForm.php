<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('registration_id')
                    ->relationship('registration', 'id')
                    ->required(),
                Select::make('registration_category')
                    ->options(['Domestic' => 'Domestic', 'International' => 'International'])
                    ->required(),
                Select::make('currency')
                    ->options(['IDR' => 'I d r', 'USD' => 'U s d'])
                    ->required(),
                TextInput::make('base_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('unique_code')
                    ->numeric(),
                TextInput::make('final_amount')
                    ->numeric(),
                TextInput::make('proof_of_transfer_path'),
                Toggle::make('needs_invoice')
                    ->required(),
                Select::make('payment_status')
                    ->options(['unpaid' => 'Unpaid', 'pending_verification' => 'Pending verification', 'paid' => 'Paid'])
                    ->default('unpaid')
                    ->required(),
                TextInput::make('verified_by')
                    ->numeric(),
                DateTimePicker::make('verified_at'),
            ]);
    }
}
