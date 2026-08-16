<?php

namespace App\Filament\Resources\Fixtures\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FixtureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
                ->label('Kategorija')
                ->relationship('category', 'name')
                ->required(),
            TextInput::make('opponent')->label('Protivnik')->required()->maxLength(255),
            Toggle::make('is_home')->label('Domaća utakmica')->default(true),
            DateTimePicker::make('kickoff_at')->label('Datum i vrijeme')->required()->displayFormat('d.m.Y. H:i'),
            TextInput::make('goals_for')->label('Naši golovi')->numeric()->minValue(0)
                ->helperText('Ostavi prazno ako utakmica još nije odigrana.'),
            TextInput::make('goals_against')->label('Golovi protivnika')->numeric()->minValue(0),
            TextInput::make('competition')->label('Natjecanje')->maxLength(255),
            TextInput::make('season')->label('Sezona')->placeholder('2025/2026')->maxLength(255),
        ]);
    }
}
