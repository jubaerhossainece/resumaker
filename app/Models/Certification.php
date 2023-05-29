<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    public function certifiable()
    {
        return $this->morphTo(__FUNCTION__, 'certifiable_type', 'certifiable_id');
    }
}
