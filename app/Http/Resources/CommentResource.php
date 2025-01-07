<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'rating' => $this->rating,
            'user' => $this->user->only('id', 'name'),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
