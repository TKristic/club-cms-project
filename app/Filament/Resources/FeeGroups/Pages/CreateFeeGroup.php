<?php

namespace App\Filament\Resources\FeeGroups\Pages;

use App\Filament\Resources\FeeGroups\FeeGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeGroup extends CreateRecord
{
    protected static string $resource = FeeGroupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['club_id'] = \App\Models\Club::value('id') ?? 1;
        return $data;
    }
}
