<?php

namespace App\Filament\Resources\Clubs\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClubForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Naziv kluba')->required(),
            FileUpload::make('logo')->label('Logo kluba')->image()->disk('public')->directory('club'),
            ColorPicker::make('primary_color')->label('Primarna boja')->required(),
            ColorPicker::make('secondary_color')->label('Sekundarna boja')->required(),
            TextInput::make('contact_email')->label('Kontakt e-mail')->email(),
            TextInput::make('contact_phone')->label('Kontakt telefon'),
            TextInput::make('address')->label('Adresa'),
            TextInput::make('iban')->label('IBAN kluba'),
            TextInput::make('hns_url')->label('HNS semafor link')->url()
                ->helperText('Poveznica na HNS semafor (za automatske rezultate — koristi se kasnije).'),
        ]);
    }
}
