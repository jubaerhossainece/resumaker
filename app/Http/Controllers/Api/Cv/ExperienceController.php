<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('experiences')->firstOrFail();
        
        return successResponseJson($cv->experiences);
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'organization' => 'required|string',
            'job_title' => 'required|string',
            'responsibilities_achievements' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        $experience = new Experience();
        $experience->organization = $request->organization;
        $experience->job_title = $request->job_title;
        $experience->responsibilities_achievements = $request->responsibilities_achievements;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->city = $request->city;
        $experience->country = $request->country;
        $cv->experiences()->save($experience);

        return successResponseJson($cv->experiences()->findOrFail($experience->id), 'Your experience information saved in database');
    }


    public function update(Request $request, $id, $exp_id)
    {
        $request->validate([
            'organization' => 'required|string',
            'job_title' => 'required|string',
            'responsibilities_achievements' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $experience = $cv->experiences()->findOrFail($exp_id);
        $experience->organization = $request->organization;
        $experience->job_title = $request->job_title;
        $experience->responsibilities_achievements = $request->responsibilities_achievements;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->city = $request->city;
        $experience->country = $request->country;
        $experience->save();
        
        return successResponseJson($cv->experiences()->findOrFail($exp_id), 'Your experience information updated');
    }

    public function destroy($id, $exp_id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $exp = $cv->experiences()->findOrFail($exp_id);
        $exp->delete();
        
        return successResponseJson($cv->experiences()->get(), 'Your experience information deleted');
    }
}
