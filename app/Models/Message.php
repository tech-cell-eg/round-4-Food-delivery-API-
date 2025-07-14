<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        "conversation_id",
        "sender_id",
        "type",
        "content",
        "seen_at"
    ];

    protected $casts = [
        "seen_at" => "datetime",
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }


}
