<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['club_id', 'name', 'sort_order'])]
class Category extends Model
{
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function players(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Player::class);
    } 
}