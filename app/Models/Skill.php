<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\morphedByMany;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function cvs()
    {
        return $this->morphedByMany(CvUser::class, 'skillable');
    }

    public function resumes()
    {
        return $this->morphedByMany(ResumeUser::class, 'skillable');
    }
}
