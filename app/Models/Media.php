<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['gallery_id', 'caption', 'path'])]
class Media extends Model
{
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}