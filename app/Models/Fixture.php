<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['club_id', 'category_id', 'opponent', 'is_home', 'kickoff_at', 'goals_for', 'goals_against', 'competition', 'season'])]
class Fixture extends Model
{
    protected function casts(): array
    {
        return ['kickoff_at' => 'datetime', 'is_home' => 'boolean'];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsPlayedAttribute(): bool
    {
        return $this->goals_for !== null && $this->goals_against !== null;
    }

    public function getScorelineAttribute(): string      // npr. "2:1" ili "—"
    {
        return $this->is_played ? "{$this->goals_for}:{$this->goals_against}" : '—';
    }

    public function getResultLabelAttribute(): ?string   // CALCULATED ishod
    {
        if (! $this->is_played) {
            return null;
        }
        return match (true) {
            $this->goals_for > $this->goals_against => 'Pobjeda',
            $this->goals_for < $this->goals_against => 'Poraz',
            default => 'Neriješeno',
        };
    }

    public function scopePlayed($q)
    {
        return $q->whereNotNull('goals_for');
    }

    public function scopeUpcoming($q)
    {
        return $q->whereNull('goals_for');
    }

    public function getDisplayScoreAttribute(): string
    {
        if (! $this->is_played) {
            return '—';
        }
        return $this->is_home
            ? "{$this->goals_for}:{$this->goals_against}"
            : "{$this->goals_against}:{$this->goals_for}";
    }
}