<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['club_id', 'user_id', 'title', 'slug', 'excerpt', 'body', 'featured_image', 'published_at'])]
class News extends Model
{
    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function author(): BelongsTo            // LOOKUP izvor za #8
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDaysSincePublishedAttribute(): ?int   // CALCULATED za #8
    {
        return $this->published_at ? (int) $this->published_at->diffInDays(now()) : null;
    }

    public function scopePublished($query)         // samo objavljene (javni dio)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}