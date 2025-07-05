<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'currency',
        'currency_symbol',
        'is_active'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
