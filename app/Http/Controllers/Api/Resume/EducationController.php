<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\ResumeUser;
use Illuminate\Http\Request;
use stdClass;

class EducationController extends Controller
{
    public function get($id)
    {
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('education', 'user_id', 'id')->first();

        if($resume){
            return successResponseJson($resume->education);
        }else{
            return errorResponseJson('No resume found.', 422);
        }

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
            'grad_date' => 'required|date',
            'is_current' => 'required|boolean',
        ]);
        
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($resume){
            $education = new stdClass;
            $education->study_field = $request->study_field;
            $education->degree = $request->degree;
            $education->institution_name = $request->institution_name;
            $education->result = $request->result;
            $education->city = $request->city;
            $education->country = $request->country;
            $education->grad_date = $request->grad_date;
            $education->is_current = $request->is_current;

            $id = uniqid();
            if(is_null($resume->education)){
                $arr = array();
                $arr[$id] = $education;
                $resume->education = $arr;
                $resume->save();
            }else{
                $arr = array();
                $arr[$id] = $education;
                
                $new_array = array_merge($resume->education, $arr);
                $resume->education = $new_array;
                $resume->save();
            }
            return successResponseJson($resume->education, 'Your education information saved in database');
        }else{
            return errorResponseJson('No resume found.', 422);
        }
    }

    public function update(Request $request, $id, $cert_key)
    {
        $request->validate([
            'study_field' => 'required|string',
            'degree' => 'required|string',
            'institution_name' => 'required|string',
            'result' => 'required|numeric',
            'city' => 'required|string',
            'country' => 'required|string',
            'grad_date' => 'required|date',
            'is_current' => 'required|boolean',
        ]);

        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($resume){
            $education = new stdClass;
            $education->study_field = $request->study_field;
            $education->degree = $request->degree;
            $education->institution_name = $request->institution_name;
            $education->result = $request->result;
            $education->city = $request->city;
            $education->country = $request->country;
            $education->grad_date = $request->grad_date;
            $education->is_current = $request->is_current;

            $education_list = $resume->education;
            $education_list[$cert_key] = $education;
            $resume->education = $education_list;
            $resume->save();
    
            return successResponseJson($resume->education, 'Your education information updated in database');
        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }


    public function destroy($id, $edu_key)
    {
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($resume){
            $education_list = $resume->education;
            unset($education_list[$edu_key]);
            $resume->education = $education_list;
            $resume->save();
            return successResponseJson($resume->education, 'Your education information deleted');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
