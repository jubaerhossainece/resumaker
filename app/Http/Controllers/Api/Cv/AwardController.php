<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class AwardController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->with('awards')->first();

        if($cv){
            return successResponseJson($cv->awards);
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'award_name' => 'required|string',
            'award_details' => 'required|string',
            'awarded_by' => 'required|string',
            'awarded_date' => 'required|date',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $award = new Award();
            $award->award_name = $request->award_name;
            $award->award_details = $request->award_details;
            $award->awarded_by = $request->awarded_by;
            $award->awarded_date = $request->awarded_date;
            $cv->awards()->save($award);
    
            return successResponseJson($award, 'Your award information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $award_id)
    {
        $request->validate([
            'award_name' => 'required|string',
            'award_details' => 'required|string',
            'awarded_by' => 'required|string',
            'awarded_date' => 'required|date',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $award = $cv->awards()->find($award_id);
            $award->award_name = $request->award_name;
            $award->award_details = $request->award_details;
            $award->awarded_by = $request->awarded_by;
            $award->awarded_date = $request->awarded_date;
            $award->save();

            return successResponseJson($cv->awards()->get(), 'Your award information updated');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $award_id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $award = $cv->awards()->find($award_id);
            if($award){
                $award->delete();
                return successResponseJson($cv->awards()->get(), 'Your award information deleted');
            }
            return errorResponseJson('No award info to delete.', 422);
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
