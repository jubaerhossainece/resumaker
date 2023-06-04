<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TwoFactorAuthController extends Controller
{
    public function verify2faPage()
    {
        return view('auth.verify-2fa-page');
    }


    public function loginVerifyWith2fa(Request $request){
        try {
            $validated = Validator::make($request->all(), [
                'code' => 'required|numeric'
                ]);
            if ($validated->fails()){
                return redirect()->back()->with(['error'=> 'Invalid 2FA code!']);
            }
            $user = Admin::findOrFail(Auth::id());
            $google2fa = app('pragmarx.google2fa');
            $google2fa_secret = decrypt($user->google2fa_secret);
            if ($google2fa->verifyKey($google2fa_secret, $request->input('code'))){
                session(['2fa_verified' => true]);
                $user->google2fa_verify_status = 'verified';
                $user->save();
                return redirect()->route('dashboard.index');
            } else{
                return redirect()->back()->with(['error'=> 'Invalid 2FA code!']);
            }
        } catch (\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with(['error'=> 'Something went wrong!']);
        }
    }
}
