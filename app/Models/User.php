<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /**
     * Accessor: عدد الإشعارات غير المقروءة
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Accessor: إرجاع آخر (5) إشعارات غير مقروءة لعرضها فى القائمة المنسدلة.
     */
    public function getRecentUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->latest()->take(5)->get();
    }

    /**
     * Accessor: جميع المحادثات الخاصة بالمستخدم (كعميل أو طاهى)
     */
    public function getConversationsQuery()
    {
        // نعتمد على أن جدول conversations يحتوى على customer_id و chef_id يشيران لسجلات الجداول customers/chefs
        return \App\Models\Conversation::query()
            ->where('customer_id', $this->id)
            ->orWhere('chef_id', $this->id);
    }

    /**
     * Accessor: عدد الرسائل غير المقروءة الإجمالى
     */
    public function getUnreadMessagesCountAttribute(): int
    {
        return \App\Models\Message::whereNull('read_at')
            ->where('sender_id', '!=', $this->id)
            ->whereHas('conversation', function ($q) {
                $q->whereHas('customer', function ($q2) {
                    $q2->where('customer_id', $this->id);
                })->orWhereHas('chef', function ($q2) {
                    $q2->where('chef_id', $this->id);
                });
            })->count();
    }

    /**
     * Accessor: آخر (5) محادثات محدثة مع عدد الرسائل غير المقروءة فى كل منها.
     */
    public function getUnreadConversationsAttribute()
    {
        return $this->getConversationsQuery()
            ->with(['lastMessage', 'customer.user', 'chef.user'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->whereNull('read_at')->where('sender_id', '!=', $this->id);
            }])
            ->orderByDesc('updated_at')
            ->take(5)
            ->get();
    }


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Billable, HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
        'bio',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the customer profile if user is a customer.
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id');
    }

    /**
     * Get the chef profile if user is a chef.
     */
    public function chef()
    {
        return $this->hasOne(Chef::class, 'id');
    }
}
