<?php

namespace App\Filament\Resources\FeeGroups\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Actions\Action;

class FeeGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Naziv')->searchable()->sortable(),
                TextColumn::make('default_amount')->label('Zadani iznos')->money('EUR')->sortable(),
                TextColumn::make('billing_day')->label('Dan naplate'),
                TextColumn::make('players_count')->counts('players')->label('Igrača'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (string $state) => $state === 'aktivna' ? 'success' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('toggleStatus')
                ->label(fn ($record) => $record->status === 'aktivna' ? 'Suspendiraj' : 'Aktiviraj')
                ->icon(fn ($record) => $record->status === 'aktivna' ? 'heroicon-o-pause' : 'heroicon-o-play')
                ->color(fn ($record) => $record->status === 'aktivna' ? 'warning' : 'success')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->update([
                        'status' => $record->status === 'aktivna' ? 'suspendirana' : 'aktivna',
                    ]);
                    \Filament\Notifications\Notification::make()
                        ->title('Status promijenjen na: ' . $record->status)->success()->send();
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
