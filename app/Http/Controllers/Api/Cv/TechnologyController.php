<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class TechnologyController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('technologies', 'user_id', 'id')->first();

        if($cv){
            return successResponseJson($cv->technologies);
        }else{
            return errorResponseJson('No cv found', 422);
        }
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'technology' => 'required|string'
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv->technologies){
            $arr = [];
            $arr[uniqid()] = $request->technology;

            $item = array_merge($cv->technologies, $arr);
            $cv->technologies = $item;
            $cv->save();
        }else{
            $item = [];
            $item[uniqid()] = $request->technology;
            $cv->technologies = $item;
            $cv->save();
        }

        return successResponseJson($cv->technologies, 'Your technology information saved in database.');
    }


    public function update(Request $request, $id, $technology_key)
    {
        $request->validate([
            'technology' => 'required|string'
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $technology_list = $cv->technologies;
            $technology_list[$technology_key] = $request->technology;
            
            $cv->technologies = $technology_list;
            $cv->save();
            return successResponseJson($cv->technologies, 'Your technology information saved in database.');
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }


    public function destroy($id, $technology_key)
    {   
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $technology_list = $cv->technologies;
            unset($technology_list[$technology_key]);
            $cv->technologies = $technology_list;
            $cv->save();
            return successResponseJson($cv->technologies, 'Your technology information deleted.');
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }
}
