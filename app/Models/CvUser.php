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
        'experience' => 'object',
        'education' => 'object',
        'skills' => 'object',
        'certifications' => 'object',
        'awards' => 'object',
        'publications' => 'object',
        'references' => 'object',
    ];
}
