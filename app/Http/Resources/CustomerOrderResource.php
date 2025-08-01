<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_price' => $this->total,
            "order_number" => $this->order_number,

            'items' => $this->orderItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'dish_name' => $item->dish->name ?? null,
                    "dish_image" =>  $item->dish->image ?? null,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,

                    "order" => [
                        "order_id" => $item->order_id,
                        "order_number" => $item->order->order_number,
                        "order_status" => $item->order->status,
                        "updated_at" => $item->order->updated_at,
                    ]
                ];
            }),
        ];
    }
}
