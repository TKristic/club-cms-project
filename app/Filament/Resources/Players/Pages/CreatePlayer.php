<?php

namespace App\Filament\Resources\Players\Pages;

use App\Filament\Resources\Players\PlayerResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array {
        $data['club_id'] = \App\Models\Club::value('id') ?? 1;

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