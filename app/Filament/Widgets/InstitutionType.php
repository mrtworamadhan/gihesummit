<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class InstitutionType extends TableWidget
{
    protected static ?string $heading = 'Data Peserta & Institusi';
    
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Participant::query()->with('user')
            )
            ->columns([
                TextColumn::make('user.institution_name')
                    ->label('Nama Institusi')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Delegasi')
                    ->searchable(),

                TextColumn::make('type_of_institution')
                    ->label('Tipe Institusi')
                    ->sortable() // BISA DIURUTKAN
                    ->badge()
                    ->color('info'),

                TextColumn::make('province')
                    ->label('Provinsi / Daerah')
                    ->sortable() // BISA DIURUTKAN
                    ->searchable(),
            ])
            // INI KUNCINYA: Fitur Visual Grouping Filament
            ->groups([
                Group::make('type_of_institution')
                    ->label('Tipe Institusi')
                    ->collapsible(), // Bisa di-collapse/buka-tutup
                    
                Group::make('province')
                    ->label('Provinsi')
                    ->collapsible(),
            ])
            ->defaultPaginationPageOption(5)
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
