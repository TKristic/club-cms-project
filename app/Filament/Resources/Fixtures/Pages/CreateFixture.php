<?php

namespace App\Filament\Resources\Fixtures\Pages;

use App\Filament\Resources\Fixtures\FixtureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFixture extends CreateRecord
{
    protected static string $resource = FixtureResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['club_id'] = \App\Models\Club::value('id') ?? 1;
        return $data;
    }
}
