<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AwardResource extends JsonResource
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
            'award_name' => $this->award_name,
            'award_details' => $this->award_details,
            'awarded_by' => $this->awarded_by,
            'awarded_date' => $this->awarded_date
         ];
    }
}
