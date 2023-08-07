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


    public function store(ExperienceRequest $request, $id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $data = $resume->experiences()->create($request->validated());

        return successResponseJson(new ExperienceResource($data), 'Your experience information saved in database');
    }


    public function update(ExperienceRequest $request, $id, $exp_id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $experience = $resume->experiences()->findOrFail($exp_id);
        $result = $experience->update($request->validated());

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
