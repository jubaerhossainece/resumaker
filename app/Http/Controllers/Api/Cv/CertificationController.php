<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\CvUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CertificationController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id
            ])->with('certifications')->first();

        if($cv){
            return successResponseJson($cv->certifications);
        }else{
            return errorResponseJson('No CV found.', 422);
        }
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
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $certification = new Certification();
            $certification->name = $request->name;
            $certification->issuing_org = $request->issuing_org;
            $certification->credential_url = $request->credential_url;
            $certification->issue_date = $request->issue_date;
            $certification->exp_date = $request->exp_date;
            $certification->is_no_exp = $request->is_no_exp;
            $cv->certifications()->save($certification);
            return successResponseJson($certification, 'Your certification information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $cert_id)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => 'required|string',
            'issue_date' => 'required|date',
            'exp_date' => 'required|date',
            'is_no_exp' => 'required|boolean',
        ]);

        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->with('certifications')->first();
        
        if($cv){
            $certification = $cv->certifications->find($cert_id);
            $certification->name = $request->name;
            $certification->issuing_org = $request->issuing_org;
            $certification->credential_url = $request->credential_url;
            $certification->issue_date = $request->issue_date;
            $certification->exp_date = $request->exp_date;
            $certification->is_no_exp = $request->is_no_exp;
            $certification->save();
            return successResponseJson($cv->certifications()->get(), 'Your certification information updated in database');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }


    public function destroy($id, $cert_id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $certification = $cv->certifications()->find($cert_id);
            if($certification){
                $certification->delete();
                return successResponseJson($cv->certifications()->get(), 'Your certification information deleted');
            }
            return errorResponseJson('No certification info to delete.', 422);
            
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
