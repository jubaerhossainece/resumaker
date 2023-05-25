<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $casts = [
        'personal_info' => 'object',
        'experience' => 'array',
        'education' => 'array',
        'skills' => 'array',
        'technologies' => 'array',
        'certifications' => 'array',
        'awards' => 'array',
        'publications' => 'array',
        'references' => 'array',
    ];
}
