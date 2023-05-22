<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class ReferenceController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->select('publications', 'user_id', 'id')->first();

        if($cv){
            return successResponseJson($cv->publications);
        }else{
            return errorResponseJson('No cv found.', 422);
        }
    }


    public function save(Request $request, $id)
    {
        $request->validate([
            'publication_title' => 'required|string',
            'publisher' => 'required|string',
            'published_in' => 'sometimes|string',
            'publication_url' => 'required|url',
            'publication_date' => 'required|date',
            'description' => 'required|string',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $publication = new stdClass;
            $publication->publication_title = $request->publication_title;
            $publication->publisher = $request->publisher;
            $publication->published_in = $request->published_in;
            $publication->publication_url = $request->publication_url;
            $publication->publication_date = $request->publication_date;
            $publication->description = $request->description;

            $id = uniqid();
            if(is_null($cv->publications)){
                $arr = array();
                $arr[$id] = $publication;
                $cv->publications = $arr;
                $cv->save();
            }else{
                $arr = array();
                $arr[$id] = $publication;
                
                $new_array = array_merge($cv->publications, $arr);
                $cv->publications = $new_array;
                $cv->save();
            }
    
            return successResponseJson($cv->publications, 'Your publication information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $pub_key)
    {
        $request->validate([
            'publication_title' => 'required|string',
            'publisher' => 'required|string',
            'published_in' => 'sometimes|string',
            'publication_url' => 'required|url',
            'publication_date' => 'required|date',
            'description' => 'required|string',
        ]);
        
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $publication = new stdClass;
            $publication->publication_title = $request->publication_title;
            $publication->publisher = $request->publisher;
            $publication->published_in = $request->published_in;
            $publication->publication_url = $request->publication_url;
            $publication->publication_date = $request->publication_date;
            $publication->description = $request->description;

            $publication_list = $cv->publications;
            $publication_list[$pub_key] = $publication;
            $cv->publications = $publication_list;
            $cv->save();

            return successResponseJson($cv->publications, 'Your publication information updated');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $pub_key)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
        
        if($cv){
            $publication_list = $cv->publications;
            unset($publication_list[$pub_key]);
            $cv->publications = $publication_list;
            $cv->save();
            return successResponseJson($cv->publications, 'Your publication information deleted');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
