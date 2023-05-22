<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class SkillController extends Controller
{
    public function get($id)
    {
        return $certifications = CvUser::where('skills->skill', "HTML")->select('id')->get();
        
        $user = auth()->user();

        $personal_info = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->select('personal_info', 'user_id', 'id')->first();

        return successResponseJson($personal_info);
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'skill' => 'required|array',
            'technology' => 'required|array',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $skill = new stdClass;
            $skill->technology = $request->technology;
            $skill->skill = $request->skill;
    
            $cv->skills = $skill;
            $cv->save();
    
            return successResponseJson($cv, 'Your skill information saved in database');
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }


    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'skill' => 'required|array',
    //         'technology' => 'required|array',
    //     ]);
        
    //     $cv = CvUser::where([
    //         'id' => $id,
    //         'user_id' => auth()->user()->id,
    //     ])->first();
        
    //     if($cv){
    //         $skill = new stdClass;
    //         $skill->technology = $request->technology;
    //         $skill->skill = $request->skill;
    
    //         $cv->skills = $skill;
    //         $cv->save();
    
    //         return successResponseJson($cv, 'Your skill information saved in database');
    //     }else{
    //         return errorResponseJson('No cv found.', 422);
    //     }
    // }
}
