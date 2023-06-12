<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvUser;
use App\Models\ResumeUser;
use App\Models\User;
use App\Services\GuestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $guest = GuestService::getGuest($request);
        // return response([
        //     'user' => $user,
        //     'guest' => $guest,
        // ]);
        ResumeUser::where(['user_id' => $guest->id])->update([
            'user_id' => $user->id
        ]);

        CvUser::where(['user_id' => $guest->id])->update([
            'user_id' => $user->id
        ]);

    }
}
