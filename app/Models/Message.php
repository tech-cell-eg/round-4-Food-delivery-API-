<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Notifications\MessageReceived;
use Illuminate\Support\Traits\DispatchesJobs;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_type', // 'customer' or 'chef'
        'sender_id',
        'message',
        'read_at',
    ];

    protected static function booted()
    {
        static::created(function (Message $message) {
            // Notify the other participant
            if ($message->conversation?->otherUser) {
                $message->conversation->otherUser->notify(new MessageReceived($message));
            }
        });
    }

    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->morphTo('user');
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
        }
    }
}
