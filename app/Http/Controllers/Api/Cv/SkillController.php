<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Resources\TechnologyResource;
use App\Models\CvUser;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('skills')->firstOrFail();
        return successResponseJson(['skill' => SkillResource::collection($cv->skills), 'technology' => TechnologyResource::collection($cv->technologies)]);
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'skill' => 'required|array',
            'technology' => 'nullable|array'
        ]);
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        //store and update skills
        $skill_array = [];
        for ($i=0; $i < count($request->skill); $i++) { 
            $skill_array[] = array(
                'name' => ucwords(strtolower($request->skill[$i]))
            );
        }

        Skill::insertOrIgnore($skill_array);
        $skills = Skill::whereIn('name', $request->skill)->pluck('id')->all();
        $cv->skills()->sync($skills);

        //store or update technologies
        $tech_array = [];
        for ($i=0; $i < count($request->technology); $i++) { 
            $tech_array[] = array(
                'name' => ucwords(strtolower($request->technology[$i]))
            );
        }

        Technology::insertOrIgnore($tech_array);
        $technologies = Technology::whereIn('name', $request->technology)->pluck('id')->all();
        $cv->technologies()->sync($technologies);

        return successResponseJson(['skill' => SkillResource::collection($cv->skills), 'technology' => TechnologyResource::collection($cv->technologies)], 'Your skill information saved in database.');
        
    }


    // public function update(Request $request, $id, $skill_key)
    // {
    //     $request->validate([
    //         'skill' => 'required|string'
    //     ]);
        
    //     $cv = CvUser::where([
    //         'id' => $id,
    //         'user_id' => auth()->user()->id,
    //     ])->first();

    //     if($cv){
    //         $skill_list = $cv->skills;
    //         $skill_list[$skill_key] = $request->skill;
            
    //         $cv->skills = $skill_list;
    //         $cv->save();
    //         return successResponseJson($cv->skills, 'Your skill information saved in database.');
    //     }else{
    //         return errorResponseJson('No cv found.', 422);
    //     }
    // }


    // public function destroy($id, $skill_key)
    // {   
    //     $cv = CvUser::where([
    //         'id' => $id,
    //         'user_id' => auth()->user()->id,
    //     ])->first();

    //     if($cv){
    //         $skill_list = $cv->skills;
    //         unset($skill_list[$skill_key]);
    //         $cv->skills = $skill_list;
    //         $cv->save();
    //         return successResponseJson($cv->skills, 'Your skill information deleted.');
    //     }else{
    //         return errorResponseJson('No cv found.', 422);
    //     }
    // }
}
