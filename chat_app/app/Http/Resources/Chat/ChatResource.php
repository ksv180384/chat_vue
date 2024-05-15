<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'creator' => $this->creator,
            'title' => $this->title,
//            'users' => $this->users,
            'settings' => $this->settings,
        ];

        return $result;
    }
}
