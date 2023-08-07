<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

    function projects() {
        return $this->morphMany(Project::class, 'projectable');
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
        return $this->morphMany(Reference::class, 'referenceable');
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
