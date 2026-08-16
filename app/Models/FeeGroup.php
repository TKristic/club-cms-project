<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['club_id', 'name', 'default_amount', 'billing_day', 'status'])]
class FeeGroup extends Model
{
    protected function casts(): array
    {
        return ['default_amount' => 'decimal:2'];
    }

    public function club(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->withPivot('amount_override')
            ->withTimestamps();
    }

    public function isActive(): bool
    {
        return $this->status === 'aktivna';
    }

    /** Iznos za konkretnog igrača: override ako postoji, inače grupni. */
    public function amountForPlayer(Player $player): float
    {
        $override = $player->pivot->amount_override ?? null;
        return (float) ($override ?? $this->default_amount);
    }
}