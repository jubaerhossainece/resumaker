<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    use HasFactory;

    public function cvs()
    {
        return $this->morphedByMany(CvUser::class, 'technologizable');
    }

    public function resumes()
    {
        return $this->morphedByMany(ResumeUser::class, 'technologizable');
    }
}
