<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class EducationController extends Controller
{
    public function get($id)
    {
        $user = auth()->user();

        $cv = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->select('education', 'user_id', 'id')->first();

        if($cv){
            return successResponseJson($cv->education);
        }else{
            return errorResponseJson('No cv found.', 422);
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
        
        $user = auth()->user();
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();
        
        if($cv){
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
            if(is_null($cv->education)){
                $arr = array();
                $arr[$id] = $education;
                $cv->education = $arr;
                $cv->save();
            }else{
                $arr = array();
                $arr[$id] = $education;
                
                $new_array = array_merge($cv->education, $arr);
                $cv->education = $new_array;
                $cv->save();
            }
            return successResponseJson($cv->education, 'Your education information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
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

        $cv = CvUser::where('id', $id)->first();
        
        if($cv){
            $education = new stdClass;
            $education->study_field = $request->study_field;
            $education->degree = $request->degree;
            $education->institution_name = $request->institution_name;
            $education->result = $request->result;
            $education->city = $request->city;
            $education->country = $request->country;
            $education->grad_date = $request->grad_date;
            $education->is_current = $request->is_current;

            $education_list = $cv->education;
            $education_list[$cert_key] = $education;
            $cv->education = $education_list;
            $cv->save();
    
            return successResponseJson($cv->education, 'Your education information updated in database');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }


    public function destroy($id, $edu_key)
    {
        $cv = CvUser::where('id', $id)->first();
        
        if($cv){
            $education_list = $cv->education;
            unset($education_list[$edu_key]);
            $cv->education = $education_list;
            $cv->save();
            return successResponseJson($cv->education, 'Your education information deleted');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
