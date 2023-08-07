<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\CertificationRequest;
use App\Http\Resources\CertificationResource;
use App\Models\CvUser;

class CertificationController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('certifications')->firstOrFail();
        return successResponseJson(CertificationResource::collection($cv->certifications));
    }


    public function store(CertificationRequest $request, $id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $data = $cv->certifications()->create($request->validated());
        
        return successResponseJson(new CertificationResource($data), 'Your certification information saved in database');
    }


    public function update(CertificationRequest $request, $id, $cert_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $certification = $cv->certifications()->findOrFail($cert_id);
        $result = $certification->update($request->validated());

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
