<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function change(Request $request, $id)
    {
        $request->validate([
            'template_id' => 'required|string'
        ]);

        $user = app('auth_user');

        $cv = CvUser::where(['id' => $id, 'user_id' => $user->id])->first();
        $cv->template_id = $request->template_id;
        $cv->save();

        return successResponseJson('Template changed');
    }


    public function replace($id)
    {
        $user = app('auth_user');
        $cv = CvUser::with(['personalInfo', 'experiences', 'education', 'certifications', 'awards', 'publications', 'references', 'skills', 'technologies'])->where(['id' => $id, 'user_id' => $user->id])->firstOrFail();

        //delete cv
        $cv->personalInfo()->delete();
        $cv->experiences()->delete();
        $cv->education()->delete();
        $cv->certifications()->delete();
        $cv->awards()->delete();
        $cv->publications()->delete();
        $cv->references()->delete();
        $cv->skills()->delete();
        $cv->technologies()->delete();
        $cv->delete();
        
        return successResponseJson('CV deleted from draft.');
    }
}
