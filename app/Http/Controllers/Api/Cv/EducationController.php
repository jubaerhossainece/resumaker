<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\EducationRequest;
use App\Http\Resources\EducationResource;
use App\Models\CvUser;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('education')->firstOrFail();

        return successResponseJson(EducationResource::collection($cv->education));
    }


    public function store(EducationRequest $request, $id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id, 'user_id' => $user->id])->firstOrFail();
        $data = $cv->education()->create($request->validated());

        return successResponseJson(new EducationResource($data), 'Your education information saved in database');
    }

    public function update(EducationRequest $request, $id, $edu_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $education = $cv->education()->findOrFail($edu_id);
        $result = $education->update($request->validated());

        if($result){
            return successResponseJson(new EducationResource($education), 'Your education information updated in database');
        }
        
        return errorResponseJson('Something went wrong', 500);
    }


    public function destroy($id, $edu_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $education = $cv->education()->findOrFail($edu_id);
        $education->delete();

        return successResponseJson(EducationResource::Collection($cv->education()->get()), 'Your education information deleted');
    }
}
