<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\ResumeUser;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public function get($id)
    {
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('technologies', 'user_id', 'id')->first();

        if($resume){
            return successResponseJson($resume->technologies);
        }else{
            return errorResponseJson('No resume found', 422);
        }
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'technology' => 'required|string'
        ]);
        
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($resume->technologies){
            $arr = [];
            $arr[uniqid()] = $request->technology;

            $item = array_merge($resume->technologies, $arr);
            $resume->technologies = $item;
            $resume->save();
        }else{
            $item = [];
            $item[uniqid()] = $request->technology;
            $resume->technologies = $item;
            $resume->save();
        }

        return successResponseJson($resume->technologies, 'Your technology information saved in database.');
    }


    public function update(Request $request, $id, $technology_key)
    {
        $request->validate([
            'technology' => 'required|string'
        ]);
        
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($resume){
            $technology_list = $resume->technologies;
            $technology_list[$technology_key] = $request->technology;
            
            $resume->technologies = $technology_list;
            $resume->save();
            return successResponseJson($resume->technologies, 'Your technology information saved in database.');
        }else{
            return errorResponseJson('No resume found.', 422);
        }
    }


    public function destroy($id, $technology_key)
    {   
        $resume = ResumeUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($resume){
            $technology_list = $resume->technologies;
            unset($technology_list[$technology_key]);
            $resume->technologies = $technology_list;
            $resume->save();
            return successResponseJson($resume->technologies, 'Your technology information deleted.');
        }else{
            return errorResponseJson('No resume found.', 422);
        }
    }
}
