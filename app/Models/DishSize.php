<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishSize extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dish_id',
        'size',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the dish that owns the size.
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
