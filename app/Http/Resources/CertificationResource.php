<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CertificationResource extends JsonResource
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
            'name' => $this->name,
            'issuing_org' => $this->issuing_org,
            'credential_url' => $this->credential_url,
            'issue_date' => $this->issue_date,
            'exp_date' => $this->exp_date,
            'is_no_exp' => $this->is_no_exp ? true : false,
         ];
    }
}
