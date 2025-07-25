<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'conversation_id' => $this->conversation_id,
            'sender' => [
                'id' => $this->sender?->id,
                'name' => $this->sender?->name,
                'email' => $this->sender?->email,
                'profile_image' => $this->sender?->profile_image,
                'type' => $this->sender?->type,
            ],
            'type' => $this->type,
            'content' => $this->content,
            'seen_at' => $this->seen_at ? $this->seen_at->diffForHumans() : null,
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
        ];
    }
}
