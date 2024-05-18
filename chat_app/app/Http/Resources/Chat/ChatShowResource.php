<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\User\ChatUsersResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'creator' => $this->creator,
            'title' => $this->title,
            'settings' => $this->settings,
        ];

        return $result;
    }
}
