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
            'skill' => 'required|string'
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv->skills){
            $arr = [];
            $arr[uniqid()] = $request->skill;
            
            $item = array_merge($cv->skills, $arr);
            $cv->skills = $item;
            $cv->save();
        }else{
            $item = [];
            $item[uniqid()] = $request->skill;
            $cv->skills = $item;
            $cv->save();
        }

        return successResponseJson($cv->skills, 'Your skill information saved in database.');
    }


    public function update(Request $request, $id, $skill_key)
    {
        $request->validate([
            'skill' => 'required|string'
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $skill_list = $cv->skills;
            $skill_list[$skill_key] = $request->skill;
            
            $cv->skills = $skill_list;
            $cv->save();
            return successResponseJson($cv->skills, 'Your skill information saved in database.');
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }


    public function destroy($id, $skill_key)
    {   
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $skill_list = $cv->skills;
            unset($skill_list[$skill_key]);
            $cv->skills = $skill_list;
            $cv->save();
            return successResponseJson($cv->skills, 'Your skill information deleted.');
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }
}
