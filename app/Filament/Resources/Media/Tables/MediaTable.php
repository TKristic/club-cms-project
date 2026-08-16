<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('path')->label('Slika')->disk('public'),
                \Filament\Tables\Columns\TextColumn::make('gallery.title')->label('Galerija')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('caption')->label('Opis')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Dodano')->dateTime('d.m.Y.')->sortable(),
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
