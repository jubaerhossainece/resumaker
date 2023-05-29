<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\ResumeUser;
use Illuminate\Http\Request;
use stdClass;

class EducationController extends Controller
{
    public function get($id)
    {
        $cv = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->with('education')->first();

        if($cv){
            return successResponseJson($cv->education);
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
        
        $cv = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $education = new Education();
            $education->study_field = $request->study_field;
            $education->degree = $request->degree;
            $education->institution_name = $request->institution_name;
            $education->result = $request->result;
            $education->city = $request->city;
            $education->country = $request->country;
            $education->grad_date = $request->grad_date;
            $education->is_current = $request->is_current;
            $cv->education()->save($education);
            return successResponseJson($cv->education()->find($education->id), 'Your education information saved in database');
        }else{
            return errorResponseJson('No resume found.', 422);
        }
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
            'grad_date' => 'required|date',
            'is_current' => 'required|boolean',
        ]);

        $cv = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $education = $cv->education()->find($edu_id);
            
            //check if data exists
            if($education){
                $education->study_field = $request->study_field;
                $education->degree = $request->degree;
                $education->institution_name = $request->institution_name;
                $education->result = $request->result;
                $education->city = $request->city;
                $education->country = $request->country;
                $education->grad_date = $request->grad_date;
                $education->is_current = $request->is_current;
                $education->save();
        
                return successResponseJson($education, 'Your education information updated in database');
            }
            return errorResponseJson('No education info to update.', 422);

        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }


    public function destroy($id, $edu_id)
    {
        $cv = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $education = $cv->education()->find($edu_id);
            if($education){
                $education->delete();
                return successResponseJson($cv->education()->get(), 'Your education information deleted');
            }
            return errorResponseJson('No education info to delete.', 422);

        }else{
            return errorResponseJson('Resume not found.', 422);
        }
    }
}
