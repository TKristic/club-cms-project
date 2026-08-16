<?php

namespace App\Filament\Resources\Players\Pages;

use App\Filament\Resources\Players\PlayerResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['club_id'] = \App\Models\Club::value('id') ?? 1;
        return $data;
    }
}
