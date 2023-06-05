<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'guest_id' => $this->guest_id,
            'image' => $this->image ? ($this->provider_id == null ? Storage::disk('public')->url('organization/' . $this->image) : $this->image) : null,
        ];
    }
}
