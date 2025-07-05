<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'logo_url',
        'cover_image_url',
        'opening_hours',
        'delivery_time',
        'delivery_fee',
        'minimum_order',
        'is_open',
        'country_id',
        'rating',
        'cuisine_type'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
