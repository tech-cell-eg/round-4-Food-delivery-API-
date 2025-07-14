<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        "chef_id",
        "customer_id",
        "last_message_at",
    ];

    protected $casts = [
        "last_message_at" => "datetime",
    ];

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function updateLastMessageAt()
    {
        $this->last_message_at = now();
        $this->save();
    }


}
