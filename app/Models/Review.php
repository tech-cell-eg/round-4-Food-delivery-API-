<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * إيقاف الطوابع الزمنية الافتراضية
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
        'customer_id',
        'chef_id',
        'dish_id',
        'rating',
        'comment',
    ];

    /**
     * الحقول التي يجب معاملتها كتواريخ
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم (العميل) الذي كتب المراجعة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * العلاقة مع الطاهي الذي تمت مراجعته
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chef()
    {
        return $this->belongsTo(User::class, 'chef_id');
    }

    /**
     * العلاقة مع الطبق الذي تمت مراجعته
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    /**
     * العلاقة مع الطلب الذي تمت مراجعته
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
