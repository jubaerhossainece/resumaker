<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    public function personalInfoable()
    {
        return $this->morphTo(__FUNCTION__, 'personal_infoable_type', 'personal_infoable_id');
    }
}
