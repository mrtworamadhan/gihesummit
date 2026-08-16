<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('template_path')
                    ->label('Template')
                    ->square(),
                    
                TextColumn::make('name')
                    ->label('Certificate Name')
                    ->searchable()
                    ->weight('bold'),
                    
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'main' => 'info',
                        'class' => 'warning',
                        default => 'gray',
                    }),
                    
                TextColumn::make('additionalClass.name')
                    ->label('Linked Class')
                    ->placeholder('N/A (Main Summit)'),
                    
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'main' => 'Main Summit',
                        'class' => 'Special Class',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
