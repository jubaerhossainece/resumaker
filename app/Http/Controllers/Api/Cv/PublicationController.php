<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\PublicationRequest;
use App\Http\Resources\PublicationResource;
use App\Models\CvUser;

class PublicationController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('publications')->firstOrFail();

        return successResponseJson(PublicationResource::collection($cv->publications));
    }


    public function store(PublicationRequest $request, $id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $data = $cv->publications()->create($request->validated());

        return successResponseJson(new PublicationResource($data), 'Your publication information saved in database');
    }


    public function update(PublicationRequest $request, $id, $pub_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $publication = $cv->publications()->findOrFail($pub_id);
        $result = $publication->update($request->validated());

        if($result){
            return successResponseJson(new PublicationResource($publication), 'Your publication information updated');
        }
        return errorResponseJson('Something went wrong', 500);
    }

    public function destroy($id, $pub_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $publication = $cv->publications()->findOrFail($pub_id);
        $publication->delete();
        
        return successResponseJson(PublicationResource::collection($cv->publications()->get()), 'Your publication information deleted');
    }
}
