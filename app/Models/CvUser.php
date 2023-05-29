<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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

    public function certifications()
    {
        return $this->morphMany(Certification::class, 'certifiable');
    }

    public function awards()
    {
        return $this->morphMany(Award::class, 'awardable');
    }

    public function publications()
    {
        return $this->morphMany(Publication::class, 'publicationable');
    }


    public function references()
    {
        return $this->morphMany(Publication::class, 'referenceable');
    }
}
