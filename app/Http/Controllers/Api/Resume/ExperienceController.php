<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Resume\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use App\Models\ResumeUser;
use Illuminate\Http\Request;
use stdClass;

class ExperienceController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->with('experiences')->firstOrFail();

        return successResponseJson(ExperienceResource::collection($resume->experiences));

    }


    public function save(ExperienceRequest $request, $id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $experience = new Experience();
        $experience->organization = $request->organization;
        $experience->job_title = $request->job_title;
        $experience->responsibilities_achievements = $request->responsibilities_achievements;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->city = $request->city;
        $experience->country = $request->country;
        $data = $resume->experiences()->save($experience);

        return successResponseJson(new ExperienceResource($data), 'Your experience information saved in database');
    }


    public function update(ExperienceRequest $request, $id, $exp_id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $experience = $resume->experiences()->findOrFail($exp_id);
        $experience->organization = $request->organization;
        $experience->job_title = $request->job_title;
        $experience->responsibilities_achievements = $request->responsibilities_achievements;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->city = $request->city;
        $experience->country = $request->country;
        $result = $experience->save();

        if($result){
            return successResponseJson(new ExperienceResource($experience), 'Your experience information updated');
        }
        return errorResponseJson('Something went wrong', 500);
    }

    public function destroy($id, $exp_id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $exp = $resume->experiences()->findOrFail($exp_id);
        $exp->delete();

        return successResponseJson(ExperienceResource::collection($resume->experiences()->get()), 'Your experience information deleted');
    }
}
