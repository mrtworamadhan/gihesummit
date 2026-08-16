<?php

namespace App\Filament\Resources\Registrations\Pages;

use App\Filament\Resources\Registrations\RegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRegistrations extends ListRecords
{
    protected static string $resource = RegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Registrations')
                ->badge($this->getModel()::count()),

            'incomplete' => Tab::make('Incomplete')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('payment'))
                ->badgeColor('danger')->badge($this->getModel()::whereDoesntHave('payment')->count()),

            'waiting_payment' => Tab::make('Waiting Payment')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('payment', function ($query) {
                        $query->where('payment_status', 'unpaid');
                    })
                    ->where('is_requested_confirmation', false)
                )
                ->badgeColor('warning')->badge($this->getModel()::whereHas('payment', function ($query) {
                    $query->where('payment_status', 'unpaid');
                })->where('is_requested_confirmation', false)->count()),

            'need_confirmation' => Tab::make('Need Confirmation')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('payment', function ($query) {
                        $query->where('payment_status', 'pending_verification');
                    })
                )
                ->badgeColor('info')->badge($this->getModel()::whereHas('payment', function ($query) {
                    $query->where('payment_status', 'pending_verification');
                })->count()),

            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('payment', function ($query) {
                        $query->where('payment_status', 'paid');
                    })
                )
                ->badgeColor('success')->badge($this->getModel()::whereHas('payment', function ($query) {
                    $query->where('payment_status', 'paid');
                })->count()),
        ];
    }
}
