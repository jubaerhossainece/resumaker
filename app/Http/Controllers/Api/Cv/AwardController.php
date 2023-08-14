<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\AwardRequest;
use App\Http\Resources\AwardResource;
use App\Models\CvUser;

class AwardController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('awards')->firstOrFail();
        
        return successResponseJson(AwardResource::Collection($cv->awards));
    }


    public function store(AwardRequest $request, $id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $data = $cv->awards()->create($request->validated());

        return successResponseJson(new AwardResource($data), 'Your award information saved in database');
    }


    public function update(AwardRequest $request, $id, $award_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();

        $award = $cv->awards()->findOrFail($award_id);
        $result = $award->update($request->validated());

        if($result){
            return successResponseJson(new AwardResource($award), 'Your award information updated');
        }
        return errorResponseJson('Something went wrong', 500);

    }

    /**
     * Undocumented function
     *
     * @param int $id
     * @param int $award_id
     * @return void
     */
    public function destroy($id, $award_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $award = $cv->awards()->findOrFail($award_id);
        $award->delete();
        return successResponseJson(AwardResource::collection($cv->awards()->get()), 'Your award information deleted');
    }
}
