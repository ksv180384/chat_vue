<?php

namespace App\Http\Resources;

use App\Models\Chat\ChatMessage;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessagesPaginateResource extends JsonResource
{

    public $collects = ChatMessage::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return array_merge(
            [
                'current_page' => $this->currentPage(),
                'next_page_url' => $this->nextPageUrl(),
            ],
            (new MessagesCollection($this))->toArray($request)
        );
    }
}
