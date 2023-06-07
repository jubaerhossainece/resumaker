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

        $resume = ResumeUser::where(['id' => $id, 'user_id' => $user->id])->first();
        $resume->template_id = $request->template_id;
        $resume->save();

        return successResponseJson('Template changed');
    }


    public function replace($id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::with(['personalInfo', 'experiences', 'education', 'skills', 'technologies'])->where(['id' => $id, 'user_id' => $user->id])->firstOrFail();

        //delete resume
        $resume->personalInfo()->delete();
        $resume->experiences()->delete();
        $resume->education()->delete();
        $resume->skills()->delete();
        $resume->technologies()->delete();
        $resume->delete();
        
        return successResponseJson('Resume deleted from draft.');
    }
}
