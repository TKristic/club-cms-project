<?php

namespace App\Filament\Resources\Players\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
                ->label('Kategorija')
                ->relationship('category', 'name')
                ->required(),
            TextInput::make('first_name')->label('Ime')->required()->maxLength(255),
            TextInput::make('last_name')->label('Prezime')->required()->maxLength(255),
            TextInput::make('email')
            ->label('E-mail (igrač ili roditelj)')
            ->email()
            ->required()
            ->helperText('Na ovu adresu šalju se uplatnice članarina.'),
            TextInput::make('position')->label('Pozicija')->maxLength(255),
            DatePicker::make('birth_date')->label('Datum rođenja')->displayFormat('d.m.Y.'),
            TextInput::make('jersey_number')->label('Broj dresa')->numeric()->minValue(1)->maxValue(99),
            FileUpload::make('photo_blob_upload')
                ->label('Fotografija')
                ->image()
                ->storeFiles(false)
                ->dehydrated(false),
            // Zamijenjeno radi bloba
            // FileUpload::make('photo')->label('Fotografija')->image()->disk('public')->directory('players'),
        ]);
    }
}
