<?php

namespace App\Filament\Resources\ForumPosts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ForumPostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('topic.title')
                    ->label('Tema')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable(),
                TextColumn::make('body')
                    ->label('Poruka')
                    ->limit(60),
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
