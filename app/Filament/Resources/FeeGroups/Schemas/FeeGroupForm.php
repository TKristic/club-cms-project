<?php

namespace App\Filament\Resources\FeeGroups\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeeGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Naziv grupe')->required()
                ->placeholder('npr. U-11 mjesečna članarina'),
            TextInput::make('default_amount')->label('Zadani iznos (EUR)')
                ->numeric()->required(),
            TextInput::make('billing_day')->label('Dan naplate u mjesecu')
                ->numeric()->minValue(1)->maxValue(28)->default(1)->required()
                ->helperText('1–28. Na taj dan svaki mjesec generiraju se uplatnice.'),
            Select::make('status')->label('Status')
                ->options(['aktivna' => 'Aktivna', 'suspendirana' => 'Suspendirana'])
                ->default('aktivna')->required(),
        ]);
    }
}