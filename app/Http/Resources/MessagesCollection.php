<?php

namespace App\Http\Resources;

use App\Models\Chat\ChatMessage;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessagesCollection extends ResourceCollection
{

    public $collects = ChatMessage::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection
        ];
    }
}
