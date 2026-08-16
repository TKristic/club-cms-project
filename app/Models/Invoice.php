<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['membership_fee_id', 'invoice_number', 'reference_number', 'amount', 'pdf_path'])]
class Invoice extends Model
{
    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function membershipFee(): BelongsTo
    {
        return $this->belongsTo(MembershipFee::class);
    }
}