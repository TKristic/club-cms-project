<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 'logo', 'primary_color', 'secondary_color',
    'contact_email', 'contact_phone', 'address', 'iban', 'hns_url',
])]
class Club extends Model
{
    public function categories(): HasMany {
        return $this->hasMany(Category::class);
    }

    public function players(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Player::class);
    }

    public function fixtures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Fixture::class);
    }
}