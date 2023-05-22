<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
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
        ])->select('awards', 'user_id', 'id')->first();

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
            $award = new stdClass;
            $award->award_name = $request->award_name;
            $award->award_details = $request->award_details;
            $award->awarded_by = $request->awarded_by;
            $award->awarded_date = $request->awarded_date;

            $id = uniqid();
            if(is_null($cv->awards)){
                $arr = array();
                $arr[$id] = $award;
                $cv->awards = $arr;
                $cv->save();
            }else{
                $arr = array();
                $arr[$id] = $award;
                
                $new_array = array_merge($cv->awards, $arr);
                $cv->awards = $new_array;
                $cv->save();
            }
    
            return successResponseJson($cv->awards, 'Your award information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $award_key)
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
            $award = new stdClass;
            $award->award_name = $request->award_name;
            $award->award_details = $request->award_details;
            $award->awarded_by = $request->awarded_by;
            $award->awarded_date = $request->awarded_date;

            $award_list = $cv->awards;
            $award_list[$award_key] = $award;
            $cv->awards = $award_list;
            $cv->save();

            return successResponseJson($cv->awards, 'Your award information updated');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $award_key)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $award_list = $cv->awards;
            unset($award_list[$award_key]);
            $cv->awards = $award_list;
            $cv->save();
            return successResponseJson($cv->awards, 'Your award information deleted');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
