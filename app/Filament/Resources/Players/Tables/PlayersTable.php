<?php

namespace App\Filament\Resources\Players\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class PlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')->label('Foto')->disk('public')->circular(),
                TextColumn::make('full_name')->label('Ime')
                    ->state(fn (\App\Models\Player $record) => $record->full_name)
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('category.name')->label('Kategorija')->sortable(),   // lookup
                TextColumn::make('position')->label('Pozicija'),
                TextColumn::make('jersey_number')->label('Br.')->sortable(),
                TextColumn::make('age')->label('Dob')
                    ->state(fn (\App\Models\Player $record) => $record->age),         // calculated
                TextColumn::make('email')->label('E-mail')->searchable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategorija')
                    ->relationship('category', 'name'),
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
