<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'preferred_payment_method',
        "status",
        "first_name",
        "last_name",
        "phone",
        "address",
        "profile_image",
        "bio"
    ];

    /**
     * Get the user that owns the customer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Get the addresses for the customer.
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get the cart for the customer.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get the customer's favorites.
     */
    public function favorite_dishs()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get the customer's reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

}
