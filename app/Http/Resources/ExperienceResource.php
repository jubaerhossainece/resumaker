<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'organization' => $this->organization,
            'job_title' => $this->job_title,
            'responsibilities_achievements' => $this->responsibilities_achievements,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'city' => $this->city,
            'country' => $this->country,
         ];
    }
}
