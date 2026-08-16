<?php

namespace App\Filament\Resources\Clubs\Pages;

use App\Filament\Resources\Clubs\ClubResource;
use App\Models\Club;
use Filament\Resources\Pages\EditRecord;

class EditClub extends EditRecord
{
    protected static string $resource = ClubResource::class;

    // uvijek otvori jedini klub (id iz baze), bez {record} u ruti
    public function mount(int | string | null $record = null): void
    {
        parent::mount(Club::query()->firstOrFail()->getKey());
    }

    public function getTitle(): string
    {
        return 'Postavke kluba';
    }

    public function getBreadcrumbs(): array
    {
        return []; // makni "Clubs > club > Uredi"
    }

    protected function getHeaderActions(): array
    {
        return []; // makni dugme Obriši
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getResource()::getUrl('index');
    }
}