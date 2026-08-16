<?php

namespace App\Filament\Resources\News\Schemas;

use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->label('Naslov')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                    if (($get('slug') ?? '') !== Str::slug($old ?? '')) {
                        return;
                    }
                    $set('slug', Str::slug($state ?? ''));
                }),
            TextInput::make('slug')
                ->required()->maxLength(255)
                ->unique(News::class, 'slug', fn ($record) => $record)
                ->disabled(fn (?string $operation) => $operation === 'edit'),
            TextInput::make('excerpt')->label('Sažetak')->maxLength(255)->columnSpanFull(),
            Textarea::make('body')->label('Sadržaj')->required()->rows(10)->columnSpanFull(),
            FileUpload::make('featured_image')
            ->label('Naslovna slika')
            ->image()
            ->disk('public')          // ← dodano
            ->directory('news'),
            DateTimePicker::make('published_at')
                ->label('Datum objave')
                ->helperText('Ostavi prazno za skicu (draft).'),
        ]);
    }
}