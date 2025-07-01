<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishSize extends Model
{
    use HasFactory;

    /**
     * إيقاف الطوابع الزمنية
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * الحقول القابلة للتعبئة الجماعية
     *
     * @var array
     */
    protected $fillable = [
        'dish_id',
        'name',
        'price_multiplier',
    ];

    /**
     * العلاقة مع الطبق
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
