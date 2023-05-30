<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\CvUser;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('awards')->firstOrFail();
        return successResponseJson($cv->awards);
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
        $cv->awards()->save($award);

        return successResponseJson($award, 'Your award information saved in database');
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
        $award->save();

        return successResponseJson($cv->awards()->findOrFail($award_id), 'Your award information updated');    
    }

    public function destroy($id, $award_id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $award = $cv->awards()->findOrFail($award_id);
        $award->delete();
        return successResponseJson($cv->awards()->get(), 'Your award information deleted');
    }
}
