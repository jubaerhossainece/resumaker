<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\Experience;
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
        ])->with('experiences')->first();

        if($resume){
            return successResponseJson($resume->experiences);
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
            $experience = new Experience();
            $experience->organization = $request->organization;
            $experience->job_title = $request->job_title;
            $experience->responsibilities_achievements = $request->responsibilities_achievements;
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->city = $request->city;
            $experience->country = $request->country;
            $resume->experiences()->save($experience);
    
            return successResponseJson($experience, 'Your experience information saved in database');
        }else{
            return errorResponseJson('No resume found.', 422);
        }
    }


    public function update(Request $request, $id, $exp_id)
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
            $experience = $resume->experiences->find($exp_id);
            $experience->organization = $request->organization;
            $experience->job_title = $request->job_title;
            $experience->responsibilities_achievements = $request->responsibilities_achievements;
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->city = $request->city;
            $experience->country = $request->country;
            $experience->save();

            return successResponseJson($experience, 'Your experience information updated');
        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }

    public function destroy($id, $exp_id)
    {
        $resume = ResumeUser::where('id', $id)->first();
        
        if($resume){
            $exp = $resume->experiences->find($exp_id);
            if($exp){
                $exp->delete();
                return successResponseJson($resume->experiences()->get(), 'Your experience information deleted');
            }
            return errorResponseJson('No experience info to delete.', 422);
        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }
}
