<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Model
{
    use HasRoles;

    public $timestamps = false;

    protected $fillable = [
        "id",
        "status",
        "last_login_at"
    ];

    protected $casts = [
        "last_login_at" => "datetime",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
