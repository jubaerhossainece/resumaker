<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class SocialAuthController extends Controller
{
    
    public function redirectToProvider(Request $request)
    {
        $request->validate([
            'token' => 'required|string'
        ]);
        
        $token = $request->token;
        $provider = $request->provider; 
        
        $value = User::class.'::'.($provider);
        $providerUrl = constant($value);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->get($providerUrl);
        

        if(isset(json_decode($response)->error)){
            return errorResponseJson('An invalid token was sent',422);
        }

        return $this->providerLogin(json_decode($response), $provider);
    }

    // /**
    //  * Obtain the user information from Facebook.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function handleProviderCallback($provider)
    // {
    //     $getInfo = Socialite::driver($provider)->stateless()->user()->getId();
    //     return response()->json([
    //         'data' => $getInfo
    //     ]);
    //     $checkUser = User::where('email', $getInfo->email)
    //                 ->where('provider_id', null)
    //                 ->first();
    //     if ($checkUser) {
    //         session()->flash('login_error','Email already taken.');
    //         return redirect()->to('/login');
    //         //return view('auth.login');
    //     }
    //     $user = $this->createUser($getInfo,$provider); 
    //     auth()->login($user);
        
    //     $url = session('url.intended', '/');
    //     return redirect()->to($url);
    // }


    public function providerLogin($response, $provider)
    {
        // return $response->name;
        $provider_id = $response->sub;
        $user = User::where([
            'provider_id' => $provider_id,
        ])->first();

        if($user){
            return successResponseJson([
            'access_token' => $user->createToken('authToken')->plainTextToken,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)], 
            'You are logged in.');
        }

        $result = DB::table('users')->insert([
            'provider_id' => $provider_id,
            'name' => $response->name,
            'email' => $response->email,
            'image' => $response->picture,
            'password' => Hash::make($provider_id)
        ]);

        $user = User::where([
            'provider_id' => $provider_id,
        ])->first();

        if($result){
            return successResponseJson([
                'access_token' => $user->createToken('authToken')->plainTextToken,
                'token_type' => 'Bearer',
                'user' => new UserResource($user)
            ], 'You are logged in.');
        }else{
            return errorResponseJson('Something went wrong',422);
        }
    }
}
