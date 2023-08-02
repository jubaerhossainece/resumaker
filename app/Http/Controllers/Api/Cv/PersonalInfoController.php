<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\PersonalInfoRequest;
use App\Http\Resources\PersonalInfoResource;
use App\Models\CvUser;
use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\GuestService;
use Illuminate\Support\Str;

class PersonalInfoController extends Controller
{
    public function get($id)
    {
        $user = app('auth_user');
        $cv = CvUser::with('personalInfo')->where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        if($cv){
            return successResponseJson(new PersonalInfoResource($cv->personalInfo));
        }else{
            return errorResponseJson('No cv found', 422);
        }
    }


    public function store(PersonalInfoRequest $request)
    {
        $request->validate([
            'template_id' => 'required',
        ]);

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

        $cv = new CvUser;
        $cv->user_id = $user->id;
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
            $filename_with_ext = Str::random(20).time() . '.' . $extension;
            $request->file('image')->storeAs($path, $filename_with_ext);
            $personal_info->image = $filename_with_ext;
        }
        $info = $cv->personalInfo()->save($personal_info);
 
        $data = [
            'guest_id' => $user->guest_id ?? null,
            'cv_id' => $cv->id,
            'personal_info' => new PersonalInfoResource($info),
        ];

        return successResponseJson($data, 'Your personal information saved in database');
    }


    public function update(PersonalInfoRequest $request, $id, $info_id)
    {
        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
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
            $filename_with_ext = Str::random(20).time() . '.' . $extension;

            // delete previous image
            if (isset($personal_info->image)) {
                if(Storage::exists($path .'/'. $personal_info->image)){
                    Storage::delete($path.'/'.$personal_info->image);
                }
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
