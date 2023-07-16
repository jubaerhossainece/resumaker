<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CvResource;
use App\Models\CvUser;
use Illuminate\Http\Request;

class CvController extends Controller
{
    public function show($id)
    {
        $cv = CvUser::with('personalInfo', 'experiences', 'education', 'certifications', 'awards', 'publications', 'references', 'skills', 'technologies')->where('id', $id)->first();
        
        if($cv){
            return successResponseJson(new CvResource($cv));
        }else{
            return errorResponseJson('No cv found', 422);
        }
    }
}
