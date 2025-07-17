<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'chef_id',
        'customer_id',
        'address_id',
        'subtotal',
        'coupon_id',
        'discount',
        'delivery_fee',
        'tax',
        'total',
        'order_number',
        'status',
        'notes',
    ];

    public function logOrderStatus($status, $note = null)
    {
        OrderStatusHistory::create([
            'order_id'   => $this->id,
            'status'     => $status,
            'note'       => $note,
            'changed_by' => Auth::id(),
            'created_at' => now(),
        ]);
    }

    /**
     * العلاقة مع العميل
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function genNumber()
    {
        return 'ORD-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);
    }

    /**
     * العلاقة مع العنوان
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * العلاقة مع الكوبون
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * العلاقة مع عناصر الطلب
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * العلاقة مع الأطباق عبر عناصر الطلب
     */
    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'order_items');
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function completedOrders()
    {
        return $this->where('status', 'completed')->get();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * العلاقة مع سجل تغييرات حالة الطلب
     */
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }
}
