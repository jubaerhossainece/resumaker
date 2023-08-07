<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\CvUser;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        return $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->with('projects')->firstOrFail();
        
        return successResponseJson(ProjectResource::Collection($cv->projects));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request, $id)
    {

        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $data = $cv->projects()->create($request->validated());

        return successResponseJson(new ProjectResource($data), 'Your project information saved in database');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id, $project_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $project = $cv->projects()->findOrFail($project_id);
        $result = $project->update($request->validated());

        if($result){
            return successResponseJson(new ProjectResource($project), 'Your project information updated in database');
        }
        return errorResponseJson('Something went wrong', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $project_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $exp = $cv->projects()->findOrFail($project_id);
        $exp->delete();
        
        return successResponseJson(ProjectResource::collection($cv->projects()->get()), 'Your experience information deleted');
    }
}
