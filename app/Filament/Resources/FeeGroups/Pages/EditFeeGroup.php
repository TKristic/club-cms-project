<?php

namespace App\Filament\Resources\FeeGroups\Pages;

use App\Filament\Resources\FeeGroups\FeeGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeeGroup extends EditRecord
{
    protected static string $resource = FeeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
