<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Naziv galerije')->required()->maxLength(255),
            Textarea::make('description')->label('Opis')->rows(3)->columnSpanFull(),
        ]);
    }
}
