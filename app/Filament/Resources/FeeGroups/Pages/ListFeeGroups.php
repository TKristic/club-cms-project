<?php

namespace App\Filament\Resources\FeeGroups\Pages;

use App\Filament\Resources\FeeGroups\FeeGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeeGroups extends ListRecords
{
    protected static string $resource = FeeGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
