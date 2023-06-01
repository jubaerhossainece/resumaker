<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalInfoResource;
use App\Models\CvUser;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonalInfoController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        $data = $cv->personalInfo;
        return successResponseJson(new PersonalInfoResource($data));
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
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


        $cv = new CvUser;
        $cv->user_id = auth()->user()->id;
        $cv->template_id = $request->template_id;
        $cv->save();
        
        $social_links = json_decode($request->social_links);

        $personal_info = new PersonalInfo;
        $personal_info->first_name = $request->first_name;
        $personal_info->last_name = $request->last_name;
        $personal_info->email = $request->email;
        $personal_info->phone = $request->phone;
        $personal_info->profession = $request->profession;
        $personal_info->city = $request->city;
        $personal_info->country = $request->country;
        $personal_info->post_code = $request->post_code;
        $personal_info->about = $request->about;
        $personal_info->social_links = $social_links;

        if ($request->hasFile('image')) {
            $path = 'public/cv/userImage';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            $request->file('image')->storeAs($path, $filename_with_ext);
            $personal_info->image = $filename_with_ext;
        }
        $info = $cv->personalInfo()->save($personal_info);
        
        $data = [
            'cv_id' => $cv->id,
            'personal_info' => new PersonalInfoResource($info),
        ];
        return successResponseJson($data, 'Your personal information saved in database');
    }


    public function update(Request $request, $id, $info_id)
    {
        $request->validate([
            'image' => 'image',
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
        ]);

        $cv = CvUser::where(['id' => $id,'user_id' => auth()->user()->id])->firstOrFail();
        
        $personal_info = $cv->personalInfo()->findOrFail($info_id);
        
        $social_links = json_decode($request->social_links);
        $personal_info->first_name = $request->first_name;
        $personal_info->last_name = $request->last_name;
        $personal_info->email = $request->email;
        $personal_info->phone = $request->phone;
        $personal_info->profession = $request->profession;
        $personal_info->city = $request->city;
        $personal_info->country = $request->country;
        $personal_info->post_code = $request->post_code;
        $personal_info->about = $request->about;
        $personal_info->social_links = $social_links;

        if ($request->hasFile('image')) {
            $path = 'public/cv/userImage';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            if (isset($cv->personal_info->image)) {
                Storage::delete($path.'/'.$cv->personal_info->image);
            }
            $request->file('image')->storeAs($path, $filename_with_ext);
            $personal_info->image = $filename_with_ext;
        }
        $result = $personal_info->save();

        if($result){
            return successResponseJson(new PersonalInfoResource($personal_info), 'Your personal information updated');
        }
        return errorResponseJson('Something went wrong', 500);
    }
}
