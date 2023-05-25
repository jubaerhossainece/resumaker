<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\ResumeUser;
use Illuminate\Http\Request;
use stdClass;

class SkillController extends Controller
{
    public function get($id)
    {
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('skills', 'user_id', 'id')->first();

        if($resume){
            return successResponseJson($resume->skills);
        }else{
            return errorResponseJson('No resume found', 422);
        }
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'skill' => 'required|array',
            'technology' => 'required|array',
        ]);
        
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($resume){
            $skill = new stdClass;
            $skill->technology = $request->technology;
            $skill->skill = $request->skill;
    
            $resume->skills = $skill;
            $resume->save();
    
            return successResponseJson($resume->skills, 'Your skill information saved in database');
        }else{
            return errorResponseJson('No resume found.', 422);
        }
    }
}
