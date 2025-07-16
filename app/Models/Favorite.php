<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'dish_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];


    /**
     * Get the customer that owns the favorite.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the dish that is favorited.
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
