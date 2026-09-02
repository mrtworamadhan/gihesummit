<?php

namespace App\Filament\Resources\Gatekeepers\Pages;

use App\Filament\Resources\Gatekeepers\GatekeeperResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGatekeeper extends EditRecord
{
    protected static string $resource = GatekeeperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
