<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChefResource extends JsonResource
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
            'name' => $this->user->name,
            'description' => $this->description,
            'location' => $this->location,
            'profile_image' => $this->user->profile_image,
            'bio' => $this->user->bio,
            'phone' => $this->user->phone,
            'status' => [
                'is_verified' => (bool) $this->is_verified,
                'is_open' => (bool) $this->is_verified // Assuming verified means open
            ],
            'rating' => [
                'average' => $this->calculateAverageRating(),
                'total_reviews' => $this->reviews->count()
            ],
            'stats' => [
                'total_dishes' => $this->dishes->count(),
                'active_dishes' => $this->dishes->where('is_available', true)->count(),
            ],
            'joined_at' => $this->created_at?->format('F j, Y, g:i A'),

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
