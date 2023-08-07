<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Resources\TechnologyResource;
use App\Models\ResumeUser;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id, 'user_id' => $user->id])->firstOrFail();

        return successResponseJson(['skill' => SkillResource::collection($resume->skills), 'technology' => TechnologyResource::collection($resume->technologies)]);

    }


    public function store(Request $request, $id)
    {
        $request->validate([
            'skill' => 'required|array',
            'technology' => 'nullable|array'
        ]);
        
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        //store and update skills
        $skill_array = [];
        for ($i=0; $i < count($request->skill); $i++) { 
            $skill_array[] = array(
                'name' => ucwords(strtolower($request->skill[$i]))
            );
        }

        Skill::insertOrIgnore($skill_array);
        $skills = Skill::whereIn('name', $request->skill)->pluck('id')->all();
        $resume->skills()->sync($skills);

        //store or update technologies
        $tech_array = [];
        for ($i=0; $i < count($request->technology); $i++) { 
            $tech_array[] = array(
                'name' => ucwords(strtolower($request->technology[$i]))
            );
        }

        Technology::insertOrIgnore($tech_array);
        $technologies = Technology::whereIn('name', $request->technology)->pluck('id')->all();
        $resume->technologies()->sync($technologies);

        return successResponseJson(['skill' => SkillResource::collection($resume->skills), 'technology' => TechnologyResource::collection($resume->technologies)], 'Your skill information saved in database.');
    }
}
