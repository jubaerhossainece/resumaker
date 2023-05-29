<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;

class PersonalInfoController extends Controller
{
    public function get($id)
    {
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();

        if($cv){
            return successResponseJson($cv->personalInfo);
        }else{
            return errorResponseJson('No CV found.', 422);
        }
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
        $personal_info->social_links = $request->social_links;

        if ($request->hasFile('image')) {
            $path = 'public/cv/userImage';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            $request->file('image')->storeAs($path, $filename_with_ext);
            $personal_info->image = $filename_with_ext;
        }

        $cv->personalInfo()->save($personal_info);
        $data = [
            'cv_id' => $cv->id,
            'personal_info' => $cv->personalInfo,
        ];
        return successResponseJson($data, 'Your personal information saved in database');
    }


    public function update(Request $request, $id)
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
        ]);

        $cv = CvUser::find($id);
        
        if($cv){
            $personal_info = $cv->personalInfo;
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

            // $personal_info->save();
            $personal_info->save();
            $data = [
                'cv_id' => $cv->id,
                'personal_info' => $cv->personalInfo,
            ];
            return successResponseJson($data, 'Your personal information updated');
        }else{
            return errorResponseJson('No CV found', 422);
        }
    }
}
