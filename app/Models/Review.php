<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'chef_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the review.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the chef that is being reviewed.
     */
    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }
}
