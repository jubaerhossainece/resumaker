<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function myInfo()
    {
        $user = auth('sanctum')->user();

        return successResponseJson(['user' => new UserResource($user)]);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $request->validated();

        $user = auth('sanctum')->user();

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('image')) {
            $path = 'organization';
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename_with_ext = time() . '.' . $extension;
            if ($user->image) {
                Storage::disk('public')->delete('organization/' . $user->photo);
            }
            $request->file('image')->storeAs(
                $path, $filename_with_ext, 'public'
            );
            $request->file('image')->storeAs($path, $filename_with_ext);
            $user->image = $filename_with_ext;
        }

        $user->save();

        return successResponseJson(new UserResource($user), 'Profile updated successfully!');
    }


    public function changePassword(Request $request)
    {

        $request->validate([
            'old_password' => 'required|different:new_password',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user = auth('sanctum')->user();

        if (Hash::check($request->old_password, $user->password)) {

            $user->password = $request->new_password;
            $user->save();

            return successResponseJson(new UserResource($user), "Password changed successfully!");
        } else {
            return errorResponseJson('Current password does not match!', 422);
        }
    }
}
