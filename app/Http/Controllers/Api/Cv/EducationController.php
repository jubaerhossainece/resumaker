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

        $education = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->select('education', 'user_id', 'id')->first();

        return successResponseJson($education);

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
            $cv->user_id = $user->id;

            $education = new stdClass;
            $education->study_field = $request->study_field;
            $education->degree = $request->degree;
            $education->institution_name = $request->institution_name;
            $education->result = $request->result;
            $education->city = $request->city;
            $education->country = $request->country;
            $education->grad_date = $request->grad_date;
            $education->is_current = $request->is_current;
            
            $cv->education = $education;
            $cv->save();
    
            return successResponseJson($cv, 'Your education information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }
}
