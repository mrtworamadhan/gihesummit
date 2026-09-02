<?php

namespace App\Filament\Resources\Gatekeepers\Pages;

use App\Filament\Resources\Gatekeepers\GatekeeperResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGatekeepers extends ListRecords
{
    protected static string $resource = GatekeeperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
