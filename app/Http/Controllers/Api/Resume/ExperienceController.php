<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\ResumeUser;
use Illuminate\Http\Request;
use stdClass;

class ExperienceController extends Controller
{
    public function get($id)
    {
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('experience', 'user_id', 'id')->first();

        if($resume){
            return successResponseJson($resume->experience);
        }else{
            return errorResponseJson('No resume found.', 422);
        }

    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'organization' => 'required|string',
            'job_title' => 'required|string',
            'responsibilities_achievements' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);
        
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($resume){
            $experience = new stdClass;
            $experience->organization = $request->organization;
            $experience->job_title = $request->job_title;
            $experience->responsibilities_achievements = $request->responsibilities_achievements;
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->city = $request->city;
            $experience->country = $request->country;

            $id = uniqid();
            if(is_null($resume->experience)){
                $arr = array();
                $arr[$id] = $experience;
                $resume->experience = $arr;
                $resume->save();
            }else{
                $arr = array();
                $arr[$id] = $experience;
                
                $new_array = array_merge($resume->experience, $arr);
                $resume->experience = $new_array;
                $resume->save();
            }
    
            return successResponseJson($resume->experience, 'Your experience information saved in database');
        }else{
            return errorResponseJson('No resume found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $exp_key)
    {
        $request->validate([
            'organization' => 'required|string',
            'job_title' => 'required|string',
            'responsibilities_achievements' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $resume = ResumeUser::where('id', $id)->first();
        if($resume){
            $experience = new stdClass;
            $experience->organization = $request->organization;
            $experience->job_title = $request->job_title;
            $experience->responsibilities_achievements = $request->responsibilities_achievements;
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->city = $request->city;
            $experience->country = $request->country;

            $experience_list = $resume->experience;
            $experience_list[$exp_key] = $experience;
            $resume->experience = $experience_list;
            $resume->save();

            return successResponseJson($resume->experience, 'Your experience information updated');
        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }

    public function destroy($id, $exp_key)
    {
        $resume = ResumeUser::where('id', $id)->first();
        
        if($resume){
            $experience_list = $resume->experience;
            unset($experience_list[$exp_key]);
            $resume->experience = $experience_list;
            $resume->save();
            return successResponseJson($resume->experience, 'Your experience information deleted');
        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }
}
