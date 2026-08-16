<?php

namespace App\Filament\Resources\AdditionalClasses\Pages;

use App\Filament\Resources\AdditionalClasses\AdditionalClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdditionalClass extends EditRecord
{
    protected static string $resource = AdditionalClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
