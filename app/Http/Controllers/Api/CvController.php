<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;

class CvController extends Controller
{
    public function show($id)
    {
        $cv = CvUser::find($id);
        unset($cv->created_at,$cv->updated_at);
        
        if($cv){
            return successResponseJson($cv);
        }else{
            return errorResponseJson('No cv found', 422);
        }
    }
}
