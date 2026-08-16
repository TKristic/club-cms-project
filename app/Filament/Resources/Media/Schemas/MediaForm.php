<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('gallery_id')
                ->label('Galerija')
                ->relationship('gallery', 'title')
                ->required(),
            TextInput::make('caption')->label('Opis slike')->maxLength(255),
            FileUpload::make('path')
                ->label('Slika')
                ->image()
                ->disk('public')        // javni disk (kao kod vijesti)
                ->directory('gallery')
                ->required(),
        ]);
    }
}