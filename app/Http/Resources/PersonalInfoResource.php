<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PersonalInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(is_null($this->resource)){
            return [];
        }

        $url = $request->getSchemeAndHttpHost();
        if($this->personal_infoable_type == 'App\Models\CvUser'){
            $path = 'public/cv/userImage';
        }else{
            $path = 'public/resume/userImage';
        }

        return [
            'id' => $this->id,
            'image' => $this->image ? $url.Storage::url($path.'/'.$this->image) : null,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'profession' => $this->profession,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'country' => $this->country,
            'post_code' => $this->post_code,
            'about' => $this->about,
            'social_links' => $this->social_links,
        ];
    }
}
