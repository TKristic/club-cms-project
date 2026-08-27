<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['club_id', 'title', 'description'])]
class Gallery extends Model
{
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function media(): HasMany {
        return $this->hasMany(Media::class);
    }
}