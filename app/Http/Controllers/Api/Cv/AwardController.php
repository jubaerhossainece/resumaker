<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Resources\AwardResource;
use App\Models\Award;
use App\Models\CvUser;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('awards')->firstOrFail();
        return successResponseJson(AwardResource::Collection($cv->awards));
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'award_name' => 'required|string',
            'award_details' => 'required|string',
            'awarded_by' => 'required|string',
            'awarded_date' => 'required|date',
        ]);
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $award = new Award();
        $award->award_name = $request->award_name;
        $award->award_details = $request->award_details;
        $award->awarded_by = $request->awarded_by;
        $award->awarded_date = $request->awarded_date;
        $data = $cv->awards()->save($award);

        return successResponseJson(new AwardResource($data), 'Your award information saved in database');
    }


    public function update(Request $request, $id, $award_id)
    {
        $request->validate([
            'award_name' => 'required|string',
            'award_details' => 'required|string',
            'awarded_by' => 'required|string',
            'awarded_date' => 'required|date',
        ]);
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $award = $cv->awards()->findOrFail($award_id);
        
        $award->award_name = $request->award_name;
        $award->award_details = $request->award_details;
        $award->awarded_by = $request->awarded_by;
        $award->awarded_date = $request->awarded_date;
        $result = $award->save();

        if($result){
            return successResponseJson(new AwardResource($award), 'Your award information updated');
        }
        
        return errorResponseJson('Something went wrong', 500);

    }

    public function destroy($id, $award_id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $award = $cv->awards()->findOrFail($award_id);
        $award->delete();
        return successResponseJson(AwardResource::collection($cv->awards()->get()), 'Your award information deleted');
    }
}
