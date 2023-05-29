<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}