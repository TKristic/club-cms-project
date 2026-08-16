<?php

namespace App\Filament\Resources\MembershipFees\Pages;

use App\Filament\Resources\MembershipFees\MembershipFeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMembershipFee extends CreateRecord
{
    protected static string $resource = MembershipFeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['club_id'] = \App\Models\Club::value('id') ?? 1;
        return $data;
    }
}
