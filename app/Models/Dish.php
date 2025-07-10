<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'chef_id',
        'name',
        'description',
        'image',
        'is_available',
        'total_rate',
        'avg_rate',
        'category_id',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'avg_rate' => 'decimal:2',
    ];

    /**
     * Get the chef that owns the dish.
     */
    public function chef()
{
    return $this->belongsTo(Chef::class, 'chef_id', "id");
}

    /**
     * Get the category that owns the dish.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the sizes for the dish.
     */
    public function sizes()
    {
        return $this->hasMany(DishSize::class);
    }

    /**
     * Get the ingredients for the dish.
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'dish_ingredients');
    }

    /**
     * Get the cart items for the dish.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    /**
     * Get the favorites for the dish.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the reviews for the dish.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
