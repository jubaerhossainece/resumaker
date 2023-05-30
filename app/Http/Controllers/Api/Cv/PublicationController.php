<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->with('publications')->firstOrFail();

        return successResponseJson($cv->publications);
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
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $publication = new Publication();
        $publication->publication_title = $request->publication_title;
        $publication->publisher = $request->publisher;
        $publication->published_in = $request->published_in;
        $publication->publication_url = $request->publication_url;
        $publication->publication_date = $request->publication_date;
        $publication->description = $request->description;
        $cv->publications()->save($publication);

        return successResponseJson($cv->publications()->findOrFail($publication->id), 'Your publication information saved in database');
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
        
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $publication = $cv->publications()->findOrFail($pub_id);
        $publication->publication_title = $request->publication_title;
        $publication->publisher = $request->publisher;
        $publication->published_in = $request->published_in;
        $publication->publication_url = $request->publication_url;
        $publication->publication_date = $request->publication_date;
        $publication->description = $request->description;
        $publication->save();

        return successResponseJson($cv->publications()->findOrFail($pub_id), 'Your publication information updated');
    }

    public function destroy($id, $pub_id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();

        $publication = $cv->publications()->findOrFail($pub_id);
        $publication->delete();
        
        return successResponseJson($cv->publications()->get(), 'Your publication information deleted');
    }
}
