<?php

namespace App\Filament\Resources\MembershipFees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MembershipFeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('player_id')
                ->label('Igrač')
                ->relationship('player', 'last_name')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name}")
                ->searchable()
                ->required(),
            TextInput::make('season')->label('Sezona')->placeholder('2025/2026')->required(),
            TextInput::make('amount')->label('Iznos (EUR)')->numeric()->required(),
            DatePicker::make('due_date')->label('Dospijeće')->required()->displayFormat('d.m.Y.'),
            Select::make('status')->label('Status')
                ->options(['nepodmireno' => 'Nepodmireno', 'placeno' => 'Plaćeno'])
                ->default('nepodmireno')->required(),
        ]);
    }
}
