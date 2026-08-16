<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')->label('Slika')->disk('public'),
                TextColumn::make('title')->label('Naslov')->searchable()->sortable(),     // sortiranje
                TextColumn::make('author.name')->label('Autor')->sortable(),              // LOOKUP polje
                TextColumn::make('published_at')->label('Objavljeno')->dateTime('d.m.Y.')->sortable(),
                TextColumn::make('days_ago')->label('Dana od objave')
                    ->state(fn (\App\Models\News $record) => $record->days_since_published), // CALCULATED polje
            ])
            ->filters([
                TernaryFilter::make('published')
                    ->label('Status objave')
                    ->placeholder('Sve')
                    ->trueLabel('Objavljeno')
                    ->falseLabel('Skice (draft)')
                    ->queries(
                        true: fn (Builder $q) => $q->whereNotNull('published_at'),
                        false: fn (Builder $q) => $q->whereNull('published_at'),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
