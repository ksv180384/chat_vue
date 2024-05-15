<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;

class MessagesPaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request = null)
    {

        return array_merge(
            [
                'current_page' => $this->currentPage(),
                'next_page_url' => $this->nextPageUrl(),
            ],
            MessageResource::collection($this->toArray()),
//            (new MessagesCollection($this))->toArray($request)
        );
    }
}
