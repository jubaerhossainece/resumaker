<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'publication_title' => $this->publication_title,
            'publisher' => $this->publisher,
            'published_in' => $this->published_in,
            'publication_url' => $this->publication_url,
            'publication_date' => $this->publication_date,
            'description' => $this->description,
        ];
    }
}
