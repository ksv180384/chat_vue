<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationSimpleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'per_page' => (int)$this->perPage(),
            'current_page' => (int)$this->currentPage(),
            'next_page_url' => $this->nextPageUrl(),
//            'path' => $this->path(),
//            'query' => $this->query,
//            'fragment' => $this->fragment,
//            'page_name' => $this->pageName,
//            'on_each_side' => $this->onEachSide,
//            'options' => $this->options,
//            'total' => (int)$this->total(),
//            'last_page' => (int)$this->lastPage(),
        ];
    }
}
