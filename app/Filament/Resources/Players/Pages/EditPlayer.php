<?php

namespace App\Filament\Resources\Players\Pages;

use App\Filament\Resources\Players\PlayerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlayer extends EditRecord
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->handleBlob($data);
    }

    protected function handleBlob(array $data): array {
        $upload = $this->data['photo_blob_upload'] ?? null;

        if ($upload) {
            $file = is_array($upload) ? reset($upload) : $upload;
            if ($file && method_exists($file, 'get')) {
                $data['photo_blob'] = $file->get();  
            }
        }

        return $data;
    }
}
