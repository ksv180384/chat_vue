<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'avatar' => $this->avatar,
            'avatar_src' => $this->avatar_src,
            'name' => $this->name,
        ];
    }

    public static function collection($resource)
    {
        parent::$wrap = 'users';
        return parent::collection($resource);
    }
}
