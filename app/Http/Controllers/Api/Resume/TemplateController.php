<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Models\ResumeUser;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function change(Request $request, $id)
    {
        $request->validate([
            'template_id' => 'required|string'
        ]);

        $user = app('auth_user');

        $cv = ResumeUser::where(['id' => $id, 'user_id' => $user->id])->first();
        $cv->template_id = $request->template_id;
        $cv->save();

        return successResponseJson('Template changed');
    }


    public function replace($id)
    {
        $user = app('auth_user');
        $cv = ResumeUser::with(['personalInfo', 'experiences', 'education', 'skills', 'technologies'])->where(['id' => $id, 'user_id' => $user->id])->firstOrFail();

        //delete cv
        $cv->personalInfo()->delete();
        $cv->experiences()->delete();
        $cv->education()->delete();
        $cv->skills()->delete();
        $cv->technologies()->delete();
        $cv->delete();
        
        return successResponseJson('Resume deleted from draft.');
    }
}
