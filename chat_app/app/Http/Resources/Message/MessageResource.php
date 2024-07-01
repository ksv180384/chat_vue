<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request|null $request
     * @return array
     */
    public function toArray($request = null): array
    {
        $result = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'chat_room_id' => $this->chat_room_id,
            'message' => $this->message,
            'created_at' => $this->created_at->format('d.m.Y H:i'),
            'updated_at' => $this->updated_at,
            'created_at_time' => $this->created_at_time,
            'created_at_date' => $this->created_at_date,
            'user' => $this->user
        ];

        return $result;
    }
}
