<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Get the dishes that use this ingredient.
     */
    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_ingredients');
    }
}
