<?php

namespace App\Filament\Resources\Fixtures\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class FixturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->label('Kategorija')->sortable(),     // lookup
                TextColumn::make('opponent')->label('Protivnik')->searchable(),
                TextColumn::make('is_home')->label('Mjesto')
                    ->formatStateUsing(fn (bool $state) => $state ? 'Doma' : 'Gosti'),
                TextColumn::make('kickoff_at')->label('Termin')->dateTime('d.m.Y. H:i')->sortable(),
                TextColumn::make('scoreline')->label('Rezultat')
                    ->state(fn (\App\Models\Fixture $r) => $r->scoreline),
                TextColumn::make('result_label')->label('Ishod')                        // calculated
                    ->state(fn (\App\Models\Fixture $r) => $r->result_label ?? '—')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Pobjeda' => 'success',
                        'Poraz' => 'danger',
                        'Neriješeno' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('category_id')->label('Kategorija')->relationship('category', 'name'),
                TernaryFilter::make('played')
                    ->label('Status')
                    ->placeholder('Sve')
                    ->trueLabel('Odigrane')
                    ->falseLabel('Nadolazeće')
                    ->queries(
                        true: fn (Builder $q) => $q->whereNotNull('goals_for'),
                        false: fn (Builder $q) => $q->whereNull('goals_for'),
                    ),
            ])
            ->defaultSort('kickoff_at', 'desc')
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
