<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;

    public bool $isQuickEdit = false;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('toggle_edit')
                ->label($this->isQuickEdit ? 'Kunci Tabel (Safe)' : 'Buka Mode Edit Cepat')
                ->color($this->isQuickEdit ? 'success' : 'warning')
                ->icon($this->isQuickEdit ? 'heroicon-o-lock-closed' : 'heroicon-o-pencil-square')
                ->action(fn () => $this->isQuickEdit = ! $this->isQuickEdit),
        ];
    }
}
