<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        try {

            if (!Auth::attempt($request->only(['email', 'password']))) {
                // throw \Illuminate\Validation\ValidationException::withMessages(['password' => 'Email & Password does not match.']);
                return redirect()->back()->withMessage('Email & Password does not match.');
            }

            $user = Admin::where('email', $request->email)->first();

            //After successful validation, check if the $user has 2FA enabled
            if ($user->google2fa_enabled == true) {
                return redirect()->route('verify2faPage');
            }
            return redirect()->route('dashboard.index');
            
        } catch (Exception $error) {
            return errorResponseJson($error->getMessage(),422);
        }
    }

    /* added */
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

}


