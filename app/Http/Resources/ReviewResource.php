<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'customer' => [
                "id" => $this->customerWithUser->user->id,
                'name' => $this->customerWithUser->user->name,
                'profile_image' => $this->customerWithUser->user->profile_image,
            ]
        ];
    }
}
