<?php

namespace App\Http\Resources;

use App\Models\Chat\ChatRoom;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatCollection extends ResourceCollection
{
    public static $wrap = 'chats';

    public $collects = ChatToUserResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Support\Collection
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
