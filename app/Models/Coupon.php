<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'chef_id',
        'discount_type',
        'discount_value',
        'description',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the chef that owns the coupon.
     */
    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }

    /**
     * Check if the coupon is expired.
     */
    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Get the orders that use this coupon.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
