<?php

namespace App\Broadcasting;

use App\Models\Order;
use App\Models\User;

class OrderChannel
{
    public function __construct()
    {
        //
    }

    public function join(User $user, Order $order): array|bool
    {
        return $user->id === $order->user_id;
    }
}



