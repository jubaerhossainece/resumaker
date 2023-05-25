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
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('skills', 'user_id', 'id')->first();

        if($cv){
            return successResponseJson($cv->skills);
        }else{
            return errorResponseJson('No cv found', 422);
        }
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
    
            return successResponseJson($cv->skills, 'Your skill information saved in database');
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
