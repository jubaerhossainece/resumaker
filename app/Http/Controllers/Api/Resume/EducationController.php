<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Resume\EducationRequest;
use App\Http\Resources\EducationResource;
use App\Models\Education;
use App\Models\ResumeUser;
use Illuminate\Http\Request;
use stdClass;

class EducationController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->with('education')->firstOrFail();

        return successResponseJson(EducationResource::collection($resume->education));
    }


    public function store(EducationRequest $request, $id)
    {   
        $user = app('auth_user');

        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $data = $resume->education()->create($request->validated());
        
        return successResponseJson(new EducationResource($data), 'Your education information saved in database');
    }

    public function update(EducationRequest $request, $id, $edu_id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id, 'user_id' => $user->id])->firstOrFail();
        
        $education = $resume->education()->findOrFail($edu_id);
        $result = $education->update($request->validated());
        
        if($result){
            return successResponseJson(new EducationResource($education), 'Your education information updated in database');
        }
        return errorResponseJson('Something went wrong', 500);
    }


    public function destroy($id, $edu_id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $education = $resume->education()->findOrFail($edu_id);
        $education->delete();

        return successResponseJson(EducationResource::collection($resume->education()->get()), 'Your education information deleted');
    }
}
