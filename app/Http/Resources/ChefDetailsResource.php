<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChefDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Group dishes by category
        $categoriesWithMeals = $this->dishes
            ->groupBy('category_id')
            ->map(function ($dishes, $categoryId) {
                return [
                    'category_id' => $categoryId,
                    'meals' => $dishes
                ];
            })->values();

        return [
            'Resturant' => [
                'id' => $this->id,
                'name' => $this->user->name,
                'description' => $this->description,
                'location' => $this->location,
                'profile_image' => $this->user->profile_image,
                'bio' => $this->user->bio,
                'phone' => $this->user->phone,
                'email' => $this->user->email,
                'status' => [
                    'is_verified' => (bool) $this->is_verified,
                    'is_open' => (bool) $this->is_verified,
                    'balance' => round((float) $this->balance, 2)
                ],
                'rating' => [
                    'average' => $this->calculateAverageRating(),
                    'total_reviews' => $this->reviews->count()
                ],
                'stats' => [
                    'total_dishes' => $this->dishes->count(),
                    'active_dishes' => $this->dishes->where('is_available', true)->count(),
                    'categories_count' => $this->dishes->pluck('category_id')->unique()->count()
                ],
                'joined_at' => $this->created_at?->toISOString()
            ],
            'menu' => [
                'categories_count' => $categoriesWithMeals->count(),
                'total_dishes' => $this->dishes->count(),
                'categories' => CategoryWithMealsResource::collection($categoriesWithMeals)
            ]
        ];
    }

    /**
     * Calculate the average rating from reviews
     */
    private function calculateAverageRating()
    {
        if ($this->reviews->count() > 0) {
            return round($this->reviews->avg('rating'), 1);
        }
        return 0.0;
    }
}
