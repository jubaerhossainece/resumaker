<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResumeResource;
use App\Models\ResumeUser;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function show($id)
    {
        $resume = ResumeUser::with('personalInfo', 'experiences', 'education', 'skills', 'technologies')->where('id', $id)->first();
        
        if($resume){
            return successResponseJson(new ResumeResource($resume));
        }else{
            return errorResponseJson('No resume found', 422);
        }
    }
}
