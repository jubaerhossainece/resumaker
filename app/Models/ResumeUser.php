<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ResumeUser extends Model
{
    use HasFactory;

    public function personalInfo()
    {
        return $this->morphOne(PersonalInfo::class, 'personal_infoable');
    }

    public function experiences()
    {
        return $this->morphMany(Experience::class, 'experienceable');
    }

    public function education()
    {
        return $this->morphMany(Education::class, 'educationable');
    }

    public function skills(): MorphToMany
    {
        return $this->morphToMany(Skill::class, 'skillable');
    }

    public function technologies(): MorphToMany
    {
        return $this->morphToMany(Technology::class, 'technologizable');
    }
}