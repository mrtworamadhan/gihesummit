<?php

namespace App\Filament\Resources\AdditionalClasses\Pages;

use App\Filament\Resources\AdditionalClasses\AdditionalClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdditionalClasses extends ListRecords
{
    protected static string $resource = AdditionalClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
