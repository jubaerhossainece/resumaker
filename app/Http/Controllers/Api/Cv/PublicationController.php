<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\Publication;
use Illuminate\Http\Request;
use stdClass;

class PublicationController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->with('publications')->first();

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
            $publication = new Publication();
            $publication->publication_title = $request->publication_title;
            $publication->publisher = $request->publisher;
            $publication->published_in = $request->published_in;
            $publication->publication_url = $request->publication_url;
            $publication->publication_date = $request->publication_date;
            $publication->description = $request->description;
            $cv->publications()->save($publication);
            return successResponseJson($publication, 'Your publication information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    public function update(Request $request, $id, $pub_id)
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
            $publication = $cv->publications()->find($pub_id);
            $publication->publication_title = $request->publication_title;
            $publication->publisher = $request->publisher;
            $publication->published_in = $request->published_in;
            $publication->publication_url = $request->publication_url;
            $publication->publication_date = $request->publication_date;
            $publication->description = $request->description;
            $publication->save();

            return successResponseJson($cv->publications()->get(), 'Your publication information updated');
        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }

    public function destroy($id, $pub_id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            $publication = $cv->publications()->find($pub_id);
            if($publication){
                $publication->delete();
                return successResponseJson($cv->publications()->get(), 'Your publication information deleted');
            }
            return errorResponseJson('No publication info to delete.', 422);

        }else{
            return errorResponseJson('CV not found.', 422);
        }
    }
}
