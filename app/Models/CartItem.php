<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'dish_id',
        'size_id',
        'quantity',
        'price',
    ];

    /**
     * العلاقة مع سلة التسوق
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * العلاقة مع الطبق
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dish()
{
    return $this->belongsTo(Dish::class);
}


    /**
     * العلاقة مع حجم الطبق
     *
     * @return App\Models\DishSize
     */
    public function size()
    {
        return $this->belongsTo(DishSize::class, 'size_id', 'id');
    }

    /**
     * حساب السعر الإجمالي لعنصر السلة
     *
     * @return float
     */
    public function getTotalPrice()
    {
        $price = $this->dish->base_price;
        if ($this->size) {
            $price *= $this->size->price_multiplier;
        }
        return $price * $this->quantity;
    }
}
