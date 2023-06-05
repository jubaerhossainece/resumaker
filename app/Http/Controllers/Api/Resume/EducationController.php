<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
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
        $cv = ResumeUser::where(['id' => $id,'user_id' => $user->id])->with('education')->firstOrFail();

        return successResponseJson(EducationResource::collection($cv->education));
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'study_field' => 'required|string',
            'degree' => 'required|string',
            'institution_name' => 'required|string',
            'result' => 'required|numeric',
            'city' => 'required|string',
            'country' => 'required|string',
            'grad_date' => 'nullable|date',
            'is_current' => 'required|boolean',
        ]);
        
        $user = app('auth_user');
        $cv = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $education = new Education();
        $education->study_field = $request->study_field;
        $education->degree = $request->degree;
        $education->institution_name = $request->institution_name;
        $education->result = $request->result;
        $education->city = $request->city;
        $education->country = $request->country;
        $education->grad_date = $request->grad_date;
        $education->is_current = $request->is_current;
        $data = $cv->education()->save($education);
        
        return successResponseJson(new EducationResource($data), 'Your education information saved in database');
    }

    public function update(Request $request, $id, $edu_id)
    {
        $request->validate([
            'study_field' => 'required|string',
            'degree' => 'required|string',
            'institution_name' => 'required|string',
            'result' => 'required|numeric',
            'city' => 'required|string',
            'country' => 'required|string',
            'grad_date' => 'nullable|date',
            'is_current' => 'required|boolean',
        ]);

        $user = app('auth_user');
        $cv = ResumeUser::where(['id' => $id, 'user_id' => $user->id])->firstOrFail();
        
        $education = $cv->education()->findOrFail($edu_id);
        $education->study_field = $request->study_field;
        $education->degree = $request->degree;
        $education->institution_name = $request->institution_name;
        $education->result = $request->result;
        $education->city = $request->city;
        $education->country = $request->country;
        $education->grad_date = $request->grad_date;
        $education->is_current = $request->is_current;
        $result = $education->save();
        
        if($result){
            return successResponseJson(new EducationResource($education), 'Your education information updated in database');
        }
        return errorResponseJson('Something went wrong', 500);
    }


    public function destroy($id, $edu_id)
    {
        $user = app('auth_user');
        $cv = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $education = $cv->education()->findOrFail($edu_id);
        $education->delete();

        return successResponseJson(EducationResource::collection($cv->education()->get()), 'Your education information deleted');
    }
}
