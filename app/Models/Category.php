<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'meal_type',
    ];

    /**
     * Get the dishes for the category.
     */
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
}
