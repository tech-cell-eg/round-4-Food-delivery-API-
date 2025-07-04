<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    protected $fillable = [
        'customer_id',
        'chef_id',
        'status', // active, closed, etc.
    ];

    /**
     * Get the customer that owns the conversation.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get the chef that owns the conversation.
     */
    public function chef(): BelongsTo
    {
        return $this->belongsTo(Chef::class, 'chef_id', 'id');
    }

    /**
     * Get the messages for the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id');
    }

    /**
     * Get the most recent message in the conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Accessor: Get the other participant's User model.
     *
     * Returns the user that represents the *other* side of the conversation
     * relative to المستخدم الحالى (Authenticated user). If أحد الطرفين غير
     * موجود أو لم يتم تسجيل الدخول، فسترجع القيمة null لتجنب الأخطاء.
     */
    public function getOtherUserAttribute()
    {
        $currentUserId = Auth::id();

        if (!$currentUserId) {
            return null;
        }

        // If the current user is the customer, return the chef's user
        if ($this->customer_id == $currentUserId) {
            return $this->chef?->user;
        }

        // If the current user is the chef, return the customer's user
        if ($this->chef_id == $currentUserId) {
            return $this->customer?->user;
        }

        // Otherwise, we cannot determine the other participant
        return null;
    }
}
