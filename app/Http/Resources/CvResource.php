<?php

namespace App\Http\Resources;

use App\Models\Award;
use App\Models\Education;
use App\Models\PersonalInfo;
use App\Models\Publication;
use Illuminate\Http\Resources\Json\JsonResource;

class CvResource extends JsonResource
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
            'template_id' => $this->template_id,
            'personal_info' => new PersonalInfoResource($this->personalInfo),
            'experiences' => ExperienceResource::collection($this->experiences),
            'education' => EducationResource::collection($this->education),
            'certifications' => CertificationResource::collection($this->certifications),
            'awards' => AwardResource::collection($this->awards),
            'references' => ReferenceResource::collection($this->references),
            'skills' => SkillResource::collection($this->skills),
            'technologies' => TechnologyResource::collection($this->technologies),
         ];
    }
}
