<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        try {
            $user=Auth::user();
            if ($user->google2fa_enabled){
                $google2fa = app('pragmarx.google2fa');
                $google2fa_secret = decrypt($user->google2fa_secret);
                $QR_Image = $google2fa->getQRCodeInline(
                    config('app.name'),
                    $user->email,
                    $google2fa_secret
                );
            } else{
                $google2fa_secret = null;
                $QR_Image = null;
            }
            return view('auth.2fa-settings', ['QR_Image' => $QR_Image, 'secret' => $google2fa_secret]);
        } catch (\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with(['error'=> 'Something went wrong!']);
        }

    }


    public function enableOrDisable2fa($status){
        try {
            if (!$status){
                return redirect()->back()->with('error', 'Invalid request!');
            }
            $user = Auth::user();
            if ($status === 'enable'){
                $google2fa = app('pragmarx.google2fa');
                $google2fa_secret = $google2fa->generateSecretKey();
                $this->updateUserSettings(
                    encrypt($google2fa_secret),
                    true,
                    'unverified'
                );
                session(['2fa_verified' => true]);
                return redirect()->route('home');
            } else{
                $this->updateUserSettings(
                   null,
                    false,
                    'unverified'
                );
                return redirect()->back()->with(['success'=> '2FA disabled successfully!']);
            }
        } catch (\Exception $e){
            return redirect()->back()->with(['error'=> 'Something went wrong!']);
        }
    }

    protected function updateUserSettings($google2fa_secret,$google2fa_enabled,$google2fa_verify_status){
        $userID = auth()->user()->id;
        Admin::where('id', $userID)->update([
            'google2fa_secret' => $google2fa_secret,
            'google2fa_enabled' => $google2fa_enabled,
            'google2fa_verify_status' => $google2fa_verify_status
        ]);
    }
}
