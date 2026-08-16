<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\IconColumn::make('is_read')->label('Pročitano')->boolean(),
                \Filament\Tables\Columns\TextColumn::make('name')->label('Ime')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('subject')->label('Naslov')->limit(40),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Primljeno')->dateTime('d.m.Y. H:i')->sortable(),        
            ])
            ->recordUrl(null)
            ->recordAction(\Filament\Actions\ViewAction::class)
            ->filters([
                
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make()
                    ->label('Pregled')
                    ->modalHeading('Poruka')
                    ->schema([
                        TextEntry::make('name')->label('Ime'),
                        TextEntry::make('email')->label('E-mail'),
                        TextEntry::make('subject')->label('Naslov'),
                        TextEntry::make('message')->label('Poruka')->columnSpanFull(),
                        TextEntry::make('created_at')->label('Primljeno')->dateTime('d.m.Y. H:i'),
                    ])
                    ->mountUsing(function ($record) {
                        if (! $record->is_read) {
                            $record->update(['is_read' => true]);
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
