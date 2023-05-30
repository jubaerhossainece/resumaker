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
        $resume = ResumeUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('experiences')->firstOrFail();

        return successResponseJson($resume->experiences);

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
        
        $resume = ResumeUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $experience = new Experience();
        $experience->organization = $request->organization;
        $experience->job_title = $request->job_title;
        $experience->responsibilities_achievements = $request->responsibilities_achievements;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->city = $request->city;
        $experience->country = $request->country;
        $resume->experiences()->save($experience);

        return successResponseJson($resume->experiences()->findOrFail($experience->id), 'Your experience information saved in database');
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

        $resume = ResumeUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $experience = $resume->experiences()->findOrFail($exp_id);
        $experience->organization = $request->organization;
        $experience->job_title = $request->job_title;
        $experience->responsibilities_achievements = $request->responsibilities_achievements;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->city = $request->city;
        $experience->country = $request->country;
        $experience->save();

        return successResponseJson($resume->experiences()->findOrFail($exp_id), 'Your experience information updated');
    }

    public function destroy($id, $exp_id)
    {
        $resume = ResumeUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        $exp = $resume->experiences()->findOrFail($exp_id);
        $exp->delete();

        return successResponseJson($resume->experiences()->get(), 'Your experience information deleted');
    }
}
