<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'status',
        'coupon_id',
    ];

    /**
     * العلاقة مع المستخدم الذي يملك السلة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * افراغ جميع عناصر السلة
     */
    public function dropItems()
    {
        $this->items()->delete();
        $this->update([
            'status' => 'empty'
        ]);
    }

    /**
     * العلاقة مع عناصر السلة
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * العلاقة مع كوبون الخصم
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * حساب المجموع الكلي للسلة
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->items->sum(function ($item) {
            $price = $item->dish->base_price;
            if ($item->size) {
                $price *= $item->size->price_multiplier;
            }
            return $price * $item->quantity;
        });
    }
}
