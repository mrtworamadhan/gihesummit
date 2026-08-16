<?php

namespace App\Filament\Resources\Galleries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Preview')
                    ->square(),
                    
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->weight('bold'),
                    
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Uploaded At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Publication Status')
                    ->trueLabel('Published Photos')
                    ->falseLabel('Hidden Photos'),
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
