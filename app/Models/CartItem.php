<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the dish (product) for the cart item.
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class, 'product_id');
    }

    /**
     * Get the total price for this cart item.
     */
    public function getTotalPrice()
    {
        return $this->price * $this->quantity;
    }
}
