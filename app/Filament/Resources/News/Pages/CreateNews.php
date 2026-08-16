<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['user_id'] = auth()->id();
        $data['club_id'] = \App\Models\Club::value('id') ?? 1;
        return $data;
    }
}
