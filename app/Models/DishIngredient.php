<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishIngredient extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dish_id',
        'ingredient_id',
    ];

    /**
     * Get the dish that owns the ingredient.
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    /**
     * Get the ingredient that owns the dish.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
