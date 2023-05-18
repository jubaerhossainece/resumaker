<?php

namespace App\Http\Controllers\Api\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use Illuminate\Http\Request;
use stdClass;

class ExperienceController extends Controller
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


    public function save(Request $request, $id)
    {
        
        $request->validate([
            'organization' => 'required|string',
            'job_title' => 'required|string',
            'responsibilities_achievements' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);
        
        $user = auth()->user();
        $cv = CvUser::where([
            'id' => $id,
            'user_id' => $user->id,
        ])->first();
        
        if($cv){

            $cv->user_id = $user->id;

            $experience = new stdClass;
            $experience->organization = $request->organization;
            $experience->job_title = $request->job_title;
            $experience->responsibilities_achievements = $request->responsibilities_achievements;
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->city = $request->city;
            $experience->country = $request->country;
    
            $cv->experience = $experience;
            $cv->save();
    
            return successResponseJson($cv, 'Your experience information saved in database');
        }else{
            return errorResponseJson('No cv found with this id.', 422);
        }
    }


    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'organization' => 'required|string',
    //         'job_title' => 'required|string',
    //         'responsibilities_achievements' => 'required|string',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date',
    //         'city' => 'required|string',
    //         'country' => 'required|string',
    //     ]);

    //     $user = auth()->user();
    //     $cv = new CvUser();
    //     $cv->user_id = $user->id;
    //     $personal_info = new stdClass;
    //     $personal_info->first_name = $request->first_name;
    //     $personal_info->last_name = $request->last_name;
    //     $personal_info->email = $request->email;
    //     $personal_info->phone = $request->phone;
    //     $personal_info->profession = $request->profession;
    //     $personal_info->city = $request->city;
    //     $personal_info->country = $request->country;
    //     $personal_info->post_code = $request->post_code;
    //     $personal_info->about = $request->about;
    //     $personal_info->social_links = $request->social_links;

    //     $cv->personal_info = $personal_info;
    //     $cv->save();

    //     return successResponseJson($cv, 'Your personal information updated');

    // }
}
