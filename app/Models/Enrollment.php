<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'club_id', 'child_first_name', 'child_last_name', 'child_birth_date',
    'parent_name', 'parent_email', 'parent_phone', 'note', 'status',
])]
class Enrollment extends Model
{
    protected function casts(): array
    {
        return ['child_birth_date' => 'date'];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}