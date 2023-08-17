<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cv\PersonalInfoRequest;
use App\Http\Resources\PersonalInfoResource;
use App\Models\CvUser;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Services\GuestService;
use App\Services\ImageService;
use Illuminate\Support\Str;

class PersonalInfoController extends Controller
{
    protected $fileService;

    public function __construct()
    {
        $this->fileService = new ImageService();
    }
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
        
        $validated = $request->validated();
        
        if ($request->hasFile('image')) {
            $filename = $this->fileService->upload($request->file('image'),'cv/userImage', null, 'public');
            $validated['image'] = $filename;
        }

        $info = $cv->personalInfo()->create($validated);
 
        $data = [
            'guest_id' => $user->guest_id ?? null,
            'cv_id' => $cv->id,
            'personal_info' => new PersonalInfoResource($info),
        ];

        return successResponseJson($data, 'Your personal information saved in database');
    }


    public function update(PersonalInfoRequest $request, $id, $info_id)
    {
        $validated = $request->validated();

        $user = app('auth_user');
        $cv = CvUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        
        $personal_info = $cv->personalInfo()->findOrFail($info_id);

        if ($request->hasFile('image')) {
            $filename = $this->fileService->upload($request->file('image'),'cv/userImage', $personal_info->image, 'public');
            $validated['image'] = $filename;
        }
        $result = $personal_info->update($validated);

        if($result){
            return successResponseJson(new PersonalInfoResource($personal_info), 'Your personal information updated');
        }
        return errorResponseJson('Something went wrong', 500);
    }
}
