<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificationResource;
use App\Models\Certification;
use App\Models\CvUser;
use App\Rules\ValidUrl;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('certifications')->firstOrFail();
        return successResponseJson(CertificationResource::collection($cv->certifications));
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => ['required', new ValidUrl],
            'issue_date' => 'required|date',
            'exp_date' => 'nullable|date',
            'is_no_exp' => 'required|boolean',
        ]);
        
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $certification = new Certification();
        $certification->name = $request->name;
        $certification->issuing_org = $request->issuing_org;
        $certification->credential_url = $request->credential_url;
        $certification->issue_date = $request->issue_date;
        $certification->exp_date = $request->exp_date;
        $certification->is_no_exp = $request->is_no_exp;
        $data = $cv->certifications()->save($certification);
        
        return successResponseJson(new CertificationResource($data), 'Your certification information saved in database');
    }


    public function update(Request $request, $id, $cert_id)
    {
        $request->validate([
            'name' => 'required|string',
            'issuing_org' => 'required|string',
            'credential_url' => 'required|string',
            'issue_date' => 'required|date',
            'exp_date' => 'nullable|date',
            'is_no_exp' => 'required|boolean',
        ]);

        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $certification = $cv->certifications()->findOrFail($cert_id);
        $certification->name = $request->name;
        $certification->issuing_org = $request->issuing_org;
        $certification->credential_url = $request->credential_url;
        $certification->issue_date = $request->issue_date;
        $certification->exp_date = $request->exp_date;
        $certification->is_no_exp = $request->is_no_exp;
        $result = $certification->save();

        if($result){
            return successResponseJson(new CertificationResource($certification), 'Your certification information updated in database');
        }

        return errorResponseJson('Something went wrong', 500);
    }


    public function destroy($id, $cert_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $certification = $cv->certifications()->findOrFail($cert_id);
        $certification->delete();

        return successResponseJson(CertificationResource::collection($cv->certifications()->get()), 'Your certification information deleted');
    }
}
