<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('child_first_name')->label('Ime djeteta')->disabled(),
            TextInput::make('child_last_name')->label('Prezime djeteta')->disabled(),
            TextInput::make('parent_name')->label('Roditelj')->disabled(),
            TextInput::make('parent_email')->label('E-mail')->disabled(),
            TextInput::make('parent_phone')->label('Telefon')->disabled(),
            Textarea::make('note')->label('Napomena')->disabled()->columnSpanFull(),
            Select::make('status')->label('Status')
                ->options(['novo' => 'Novo', 'odobreno' => 'Odobreno', 'odbijeno' => 'Odbijeno'])
                ->required(),
        ]);
    }
}
