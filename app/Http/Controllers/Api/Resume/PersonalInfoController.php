<?php

namespace App\Http\Controllers\Api\Resume;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Resume\PersonalInfoRequest;
use App\Http\Resources\PersonalInfoResource;
use App\Models\ResumeUser;
use App\Models\User;
use App\Services\GuestService;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PersonalInfoController extends Controller
{
    protected $fileService;

    public function __construct()
    {
        $this->fileService = new ImageService;
    }

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


    public function store(PersonalInfoRequest $request)
    {
        $request->validate([
            'template_id' => 'required',
        ]);

        $validated = $request->validated();

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

        if ($request->hasFile('image')) {
            $filename = $this->fileService->upload($request->file('image'),'resume/userImage', null, 'public');
            $validated['image'] = $filename;
        }
        $resume->personalInfo()->create($validated);
        
        $data = [
            'guest_id' => $user->guest_id,
            'resume_id' => $resume->id,
            'personal_info' => new PersonalInfoResource($resume->personalInfo),
        ];
        return successResponseJson($data, 'Your personal information saved in database');
    }


    public function update(PersonalInfoRequest $request, $id, $info_id)
    {
        $validated = $request->validated();

        $user = app('auth_user');
        $resume = ResumeUser::where(['id' => $id,'user_id' => $user->id])->firstOrFail();
        $personal_info = $resume->personalInfo()->findOrFail($info_id);

        if ($request->hasFile('image')) {
            $filename = $this->fileService->upload($request->file('image'),'resume/userImage', $personal_info->image, 'public');
            $validated['image'] = $filename;
        }        
        $result = $personal_info->update($validated);

        if($result){
            return successResponseJson(new PersonalInfoResource($personal_info), 'Your personal information updated');
        }
        return errorResponseJson('Something went wrong', 500);
    }
}

