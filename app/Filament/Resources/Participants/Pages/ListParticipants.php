<?php

namespace App\Filament\Resources\Participants\Pages;

use App\Filament\Resources\Participants\ParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ParticipantExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make('export_master')
                ->label('Download Master Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->exporter(ParticipantExporter::class)
                ->formats([
                    ExportFormat::Xlsx,
                    ExportFormat::Csv,
                ])
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Participants')
                ->badge($this->getModel()::count()),

            'wni' => Tab::make('WNI (Local)')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('user', function ($query) {
                        $query->where('nationality', 'Indonesia');
                    })
                )
                ->badgeColor('success')->badge($this->getModel()::whereHas('user', function ($query) {
                    $query->where('nationality', 'Indonesia');
                })->count()),

            'wna' => Tab::make('WNA (International)')
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('user', function ($query) {
                        $query->where('nationality', '!=', 'Indonesia');
                    })
                )
                ->badgeColor('warning')->badge($this->getModel()::whereHas('user', function ($query) {
                    $query->where('nationality', '!=', 'Indonesia');
                })->count()),
        ];
    }
}
