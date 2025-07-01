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
            'image_url' => "storage/" . $this->image,
            'is_available' => (bool) $this->is_available,
            'rating' => [
                'average' => round((float) $this->avg_rate, 1),
                'total_reviews' => (int) $this->total_rate
            ],
            'sizes' => DishSizeResource::collection($this->whenLoaded('sizes')),
            'ingredients' => $this->whenLoaded('ingredients', function () {
                return $this->ingredients->map(function ($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                    ];
                });
            }),
            'chef' => $this->whenLoaded('chef', function () {
                return new ChefResource($this->chef);
            }),
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id ?? null,
                    'name' => $this->category->name ?? null,
                    'image' => $this->category->image ?? null,
                    'meal_type' => $this->category->meal_type ?? null,
                    'description' => $this->category->description ?? null,
                ];
            }),
            'created_at' => $this->created_at?->format('F j, Y, g:i A'),
            'created_at' => $this->created_at?->format('F j, Y, g:i A'),
        ];
    }
}
