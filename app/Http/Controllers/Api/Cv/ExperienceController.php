<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\Experience;
use Illuminate\Http\Request;
use stdClass;

class ExperienceController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->with('experiences')->first();

        if($cv){
            return successResponseJson($cv->experiences);
        }else{
            return errorResponseJson('No cv found.', 422);
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
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $experience = new Experience();
            $experience->organization = $request->organization;
            $experience->job_title = $request->job_title;
            $experience->responsibilities_achievements = $request->responsibilities_achievements;
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->city = $request->city;
            $experience->country = $request->country;
            $cv->experiences()->save($experience);
    
            return successResponseJson($experience, 'Your experience information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
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

        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $experience = $cv->experiences->find($exp_id);
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
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $exp_id)
    {
        $cv = CvUser::where('id', $id)->first();
        
        if($cv){
            $exp = $cv->experiences->find($exp_id);
            if($exp){
                $exp->delete();
                return successResponseJson($cv->experiences()->get(), 'Your experience information deleted');
            }
            return errorResponseJson('No experience info to delete.', 422);
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
