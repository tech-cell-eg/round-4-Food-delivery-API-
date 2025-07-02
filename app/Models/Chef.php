<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chef extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'national_id',
        'balance',
        'description',
        'stripe_account_id',
        'location',
        'is_verified',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the chef profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Get the dishes for the chef.
     */
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    /**
     * Get the coupons for the chef.
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the reviews for the chef.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isVerified()
    {
        return (bool) $this->is_verified;
    }

}
