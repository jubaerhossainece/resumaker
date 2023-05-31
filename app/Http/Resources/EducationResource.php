<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
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
            'study_field' => $this->study_field,
            'degree' => $this->degree,
            'institution_name' => $this->institution_name,
            'result' => $this->result,
            'city' => $this->city,
            'country' => $this->country,
            'grad_date' => $this->grad_date,
            'is_current' => $this->is_current,
         ];
    }
}
