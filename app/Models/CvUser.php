<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvUser extends Model
{
    use HasFactory;

    protected $casts = [
        'personal_info' => 'array',
        'experience' => 'array',
        'education' => 'array',
        'skills' => 'array',
        // 'certifications' => 'array',
    ];
}
