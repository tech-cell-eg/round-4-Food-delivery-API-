<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
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
        return $this->hasOne(Customer::class, 'id', 'id');
    }

    /**
     * Get the chef profile if user is a chef.
     */
    public function chef()
    {
        return $this->hasOne(Chef::class, 'id', 'id');
    }



    public function cart()
{
    return $this->hasOne(Cart::class, 'customer_id');
}

public function favorites()
{
    return $this->hasMany(Favorite::class, 'customer_id');
}

public function addresses()
{
    return $this->hasMany(Address::class, 'customer_id');
}

    public function admin()
    {
        return $this->hasOne(Admin::class, "id", "id");
    }

}
