<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\CvUser;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('experiences')->firstOrFail();
        
        return successResponseJson(ExperienceResource::collection($cv->experiences));
    }


    public function store(ExperienceRequest $request, $id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $data = $cv->experiences()->create($request->validated());

        return successResponseJson(new ExperienceResource($data), 'Your experience information saved in database');
    }


    public function update(ExperienceRequest $request, $id, $exp_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $experience = $cv->experiences()->findOrFail($exp_id);
        $result = $experience->update($request->validated());

        if($result){
            return successResponseJson(new ExperienceResource($experience), 'Your experience information updated in database');
        }
        return errorResponseJson('Something went wrong', 500);
    }

    public function destroy($id, $exp_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $exp = $cv->experiences()->findOrFail($exp_id);
        $exp->delete();
        
        return successResponseJson(ExperienceResource::collection($cv->experiences()->get()), 'Your experience information deleted');
    }
}
