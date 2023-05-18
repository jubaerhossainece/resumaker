<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class PersonalInfoController extends Controller
{
    public function get($id)
    {
        $user = auth()->user();

        $personal_info = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->select('personal_info', 'user_id', 'id')->first();

        return successResponseJson($personal_info);

    }


    public function store(Request $request)
    {
        
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'profession' => 'required|string',
            'email' => 'required|email|',
            'phone' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'post_code' => 'required|string',
            'about' => 'required|string',
            'social_links' => 'required',
            'template_id' => 'required',
        ]);

        

        $user = auth()->user();
        $cv = new CvUser();
        $cv->user_id = $user->id;
        $cv->template_id = $request->template_id;

        $personal_info = new stdClass;
        $personal_info->first_name = $request->first_name;
        $personal_info->last_name = $request->last_name;
        $personal_info->email = $request->email;
        $personal_info->phone = $request->phone;
        $personal_info->profession = $request->profession;
        $personal_info->city = $request->city;
        $personal_info->country = $request->country;
        $personal_info->post_code = $request->post_code;
        $personal_info->about = $request->about;
        $personal_info->social_links = $request->social_links;

        $cv->personal_info = $personal_info;
        $cv->save();

        return successResponseJson($cv, 'Your personal information saved in database');
    }


    public function update(Request $request)
    {
        
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'profession' => 'required|string',
            'email' => 'required|email|',
            'phone' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'post_code' => 'required|string',
            'about' => 'required|string',
            'social_links' => 'required',
            'template_id' => 'required',
        ]);

        $user = auth()->user();
        $cv = new CvUser();
        $cv->user_id = $user->id;
        $cv->template_id = $request->template_id;
        
        $personal_info = new stdClass;
        $personal_info->first_name = $request->first_name;
        $personal_info->last_name = $request->last_name;
        $personal_info->email = $request->email;
        $personal_info->phone = $request->phone;
        $personal_info->profession = $request->profession;
        $personal_info->city = $request->city;
        $personal_info->country = $request->country;
        $personal_info->post_code = $request->post_code;
        $personal_info->about = $request->about;
        $personal_info->social_links = $request->social_links;
        
        $cv->personal_info = $personal_info;
        $cv->save();

        return successResponseJson($cv, 'Your personal information updated');

    }
}
