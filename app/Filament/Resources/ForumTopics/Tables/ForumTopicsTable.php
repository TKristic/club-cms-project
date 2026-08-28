<?php

namespace App\Filament\Resources\ForumTopics\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ForumTopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Naslov')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Odgovora'),
                TextColumn::make('created_at')
                    ->label('Kreirano')
                    ->dateTime('d.m.Y. H:i')
                    ->sortable(),
            ])
            ->filters([
                //
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
