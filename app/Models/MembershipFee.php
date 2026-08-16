<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['club_id', 'fee_group_id', 'player_id', 'season', 'period', 'amount', 'due_date', 'status'])]
class MembershipFee extends Model
{
    protected function casts(): array
    {
        return ['due_date' => 'date', 'amount' => 'decimal:2'];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'placeno' && $this->due_date?->isPast();
    }

    public function feeGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FeeGroup::class);
    }
}