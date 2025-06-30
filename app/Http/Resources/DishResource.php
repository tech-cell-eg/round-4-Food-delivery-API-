<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DishResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'is_available' => $this->is_available,
            'rating' => $this->avg_rate,
            'category' => $this->category->name,
            'meal_type' => $this->category->meal_type,
            'sizes' => $this->sizes->map(function ($size) {
                return [
                    'size' => $size->size,
                    'price' => $size->price
                ];
            }),


        ];
    }
}
