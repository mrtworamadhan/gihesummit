<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use App\Filament\Resources\Payments\Widgets\PaymentStatsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            PaymentStatsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Payments')
                ->badge($this->getModel()::count()),

            'unpaid' => Tab::make('Unpaid')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('payment_status', 'unpaid')
                          ->whereHas('registration', function ($query) {
                              $query->where('is_requested_confirmation', false);
                          })
                )
                ->badgeColor('danger')->badge($this->getModel()::where('payment_status', 'unpaid')->whereHas('registration', function ($query) {
                    $query->where('is_requested_confirmation', false);
                })->count()),

            'pending_verification' => Tab::make('Pending Verification')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('payment_status', 'pending_verification')
                )
                ->badgeColor('warning')->badge($this->getModel()::where('payment_status', 'pending_verification')->count()),

            'paid' => Tab::make('Paid')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('payment_status', 'paid')
                )
                ->badgeColor('success')->badge($this->getModel()::where('payment_status', 'paid')->count()),
        ];
    }

}
