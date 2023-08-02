<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\PublicationRequest;
use App\Http\Resources\PublicationResource;
use App\Models\CvUser;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('publications')->firstOrFail();

        return successResponseJson(PublicationResource::collection($cv->publications));
    }


    public function save(PublicationRequest $request, $id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $publication = new Publication();
        $publication->publication_title = $request->publication_title;
        $publication->publisher = $request->publisher;
        $publication->published_in = $request->published_in;
        $publication->publication_url = $request->publication_url;
        $publication->publication_date = $request->publication_date;
        $publication->description = $request->description;
        $data = $cv->publications()->save($publication);

        return successResponseJson(new PublicationResource($data), 'Your publication information saved in database');
    }


    public function update(PublicationRequest $request, $id, $pub_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $publication = $cv->publications()->findOrFail($pub_id);
        $publication->publication_title = $request->publication_title;
        $publication->publisher = $request->publisher;
        $publication->published_in = $request->published_in;
        $publication->publication_url = $request->publication_url;
        $publication->publication_date = $request->publication_date;
        $publication->description = $request->description;
        $result = $publication->save();

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
