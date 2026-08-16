<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FeeGroup;

#[Fillable(['club_id', 'category_id', 'first_name', 'last_name', 'email', 'position', 'birth_date', 'jersey_number', 'photo'])]
class Player extends Model
{
    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAgeAttribute(): ?int          // CALCULATED polje (dob)
    {
        return $this->birth_date?->age;
    }

    public function membershipFees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MembershipFee::class);
    }

    public function feeGroups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FeeGroup::class)
            ->withPivot('amount_override')
            ->withTimestamps();
    }
}