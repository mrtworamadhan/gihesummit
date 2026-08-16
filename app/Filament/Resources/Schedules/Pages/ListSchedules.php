<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Days')
                ->badge($this->getModel()::count()),

            'day_1' => Tab::make('Day 1')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day', 1))
                ->badgeColor('info'),

            'day_2' => Tab::make('Day 2')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('day', 2))
                ->badgeColor('success'),
        ];
    }
}
