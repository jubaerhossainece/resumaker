<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Http\Resources\PersonalInfoResource;
use App\Http\Resources\UserResource;
use App\Models\PersonalInfo;
use App\Models\ResumeUser;
use App\Models\User;
use App\Rules\PhoneNumber;
use App\Services\GuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;

class PersonalInfoController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $resume = ResumeUser::with('personalInfo')->where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        if($resume){
            return successResponseJson(new PersonalInfoResource($resume->personalInfo));
        }else{
            return errorResponseJson('No resume found', 422);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'profession' => 'required|string',
            'email' => 'required|email|',
            'phone' => ['required', new PhoneNumber()],
            'city' => 'required|string',
            'country' => 'required|string',
            'post_code' => 'required|string',
            'about' => 'required|string',
            'social_links' => 'required',
            'template_id' => 'required',
        ]);

        //find the user using ip address and device id or create one
        $user = auth('sanctum')->user();
        if(!$user){
            $guest_id = bin2hex(random_bytes(15));

            if($request->hasHeader('guest-id') && $request->header('guest-id')){
                $user = User::where('guest_id', $request->header('guest-id'))->first();
                if(!$user){
                    $service = new GuestService();
                    $user = $service->createGuest($guest_id);
                }
            }else{
                $service = new GuestService();
                $user = $service->createGuest($guest_id);
            }
        }

        $resume = new ResumeUser;
        $resume->user_id = $user->id;
        $resume->template_id = $request->template_id;
        $resume->save();

        $social_links = json_decode($request->social_links);
        $personal_info = new PersonalInfo();
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
            $path = 'public/resume/userImage';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            $request->file('image')->storeAs($path, $filename_with_ext);
            $personal_info->image = $filename_with_ext;
        }

        $resume->personalInfo()->save($personal_info);
        $data = [
            'guest_id' => $user->guest_id,
            'resume_id' => $resume->id,
            'personal_info' => new PersonalInfoResource($resume->personalInfo),
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
            'phone' => ['required', new PhoneNumber()],
            'city' => 'required|string',
            'country' => 'required|string',
            'post_code' => 'required|string',
            'about' => 'required|string',
            'social_links' => 'required',
        ]);

        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $personal_info = $resume->personalInfo()->findOrFail($info_id);
        
        $social_links = json_decode($request->social_links);
        $personal_info = $resume->personalInfo;
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
            $path = 'public/resume/userImage';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            if (isset($resume->personal_info->image)) {
                Storage::delete($path.'/'.$resume->personal_info->image);
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

