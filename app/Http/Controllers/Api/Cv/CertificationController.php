<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CertificationController extends Controller
{
    public function get($id, $certification)
    {
        $user = auth()->user();
        
        $certifications = CvUser::where(['id' => $id])->select('certifications')->first();

        return successResponseJson($certifications);

    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => 'required|string',
            'issue_date' => 'required|date',
            'exp_date' => 'required|date',
            'is_no_exp' => 'required|boolean',
        ]);
        
        $user = auth()->user();
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();
        
        if($cv){
            $cv->user_id = $user->id;

            $certification = new stdClass;
            $certification->name = $request->name;
            $certification->issuing_org = $request->issuing_org;
            $certification->credential_url = $request->credential_url;
            $certification->issue_date = $request->issue_date;
            $certification->exp_date = $request->exp_date;
            $certification->is_no_exp = $request->is_no_exp;

            $id = uniqid();
            if(is_null($cv->certifications)){
                $arr = array();
                $arr[$id] = $certification;

                $cv->certifications = $arr;

                $cv->save();
            }else{
                
                $arr = array();
                $arr[$id] = $certification;
                
                $new_array = array_merge($cv->certifications, $arr);
                $cv->certifications = $new_array;
                $cv->save();
            }
            return successResponseJson($cv, 'Your certification information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $cert_key)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => 'required|string',
            'issue_date' => 'required|date',
            'exp_date' => 'required|date',
            'is_no_exp' => 'required|boolean',
        ]);

        $cv = CvUser::find($id);
        
        if($cv){
            $certification = new stdClass;
            $certification->name = $request->name;
            $certification->issuing_org = $request->issuing_org;
            $certification->credential_url = $request->credential_url;
            $certification->issue_date = $request->issue_date;
            $certification->exp_date = $request->exp_date;
            $certification->is_no_exp = $request->is_no_exp;

            $certifications = $cv->certifications;
            $certifications[$cert_key] = $certification;
            
            $cv->certifications = $certifications;
            $cv->save();
    
            return successResponseJson($cv->certifications, 'Your certification information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }
}
