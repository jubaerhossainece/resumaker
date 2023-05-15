<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;
use stdClass;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create($request->validated());
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            return errorResponseJson($exception->getMessage(),500);
        }

        return successResponseJson(['user' => new UserResource($user)], 'Registration success');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        try {

            if (!Auth::guard('api')->attempt($request->only(['email', 'password']))) {
                throw \Illuminate\Validation\ValidationException::withMessages(['password' => 'Email & Password does not match.']);
            }

            $user = User::where('email', $request->email)->first();
        } catch (Exception $error) {
            return errorResponseJson($error->getMessage(),422);
        }

        return successResponseJson([
            'access_token' => $user->createToken('authToken')->plainTextToken,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ], 'Login Success');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return successResponseJson(null, 'Logout successfully');
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'verification_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();
            $object = new stdClass;
            if (!$user) {
                $object->verification_code = 'No account found!';
                return response()->json(['success' => false, 'errors' => $object], 422);
            }
            if ($user->two_factor_verified_at != null && $user->two_factor_code === null) {
                return new JsonResponse(['success' => true, 'messeeage' => 'Email already verified.']);
            }

            //check expiry date of verification code
            $date = Carbon::now()->subMinutes(60);
            if ($user->two_factor_created_at <= $date) {
                $object->verification_code = 'Verification code expired';
                return response()->json(['success' => false, 'errors' => $object, 'resend_code_link' => route('api.code.resend')], 422);
            }
            if ((int)$request->verification_code === $user->two_factor_code) {
                //$user->markAsVerified();
                $user->timestamps = false;
                $user->two_factor_code = null;
                $user->two_factor_created_at = null;
                $user->two_factor_verified_at = now();
                $user->save();

                $accessToken = $user->createToken('authToken')->plainTextToken;

                if ($this->isCommissionActive()) {
                    $this->giveCommission($user, 'buyer');
                }
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully.',
                    'access_token' => $accessToken,
                    'token_type' => 'Bearer',
                    'userData' => $user,
                ]);
            }
            $object->verification_code = 'Verification code unable to match.';
            return response()->json([
                'success' => false,
                'errors' => $object,
                'resend_code_link' => route('api.code.resend'),
            ], 422);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => 'Something went wrong!',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function resendVerifyCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user->two_factor_verified_at != null) {
            return response()->json([
                'success' => false,
                'message' => 'You are already verified',
            ]);
        }

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Something went wrong!']);
        }
        $date = Carbon::parse($user->two_factor_created_at)
            ->addSeconds(60)
            ->format('Y-m-d H:i:s');

        if (now() <= $date) {
            return response()->json(['success' => false, 'message' => 'Wait few seconds',]);
        }
        $user->generateTwoFactorCode();
        $delay = now()->addSeconds(2);
        $user->notify((new TwoFactorCode())->delay($delay));
        return new JsonResponse([
            'success' => true,
            'message' => 'New verification code sent.',
            'verify_link' => route('api.email.verify'),
        ]);
    }

    public function resetPasswordMail(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = User::where('email', $request->email)->first();

        //Check if the user exists
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found !']);
        }

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Str::random(60), 'created_at' => Carbon::now()]
        );

        $tokenData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return response()->json(['success' => true, 'message' => 'A reset link has been sent to your email address.',]);
        } else {
            return response()->json(['success' => false, 'message' => 'A Network Error occurred. Please try again.',]);
        }
    }

    public function sendResetEmail($email, $token): bool
    {
        $user = User::where('email', $email)->select('name', 'email')->first();

        $url = config('custom.frontend_app_url').'reset-code/'.$token;
        try {
            $user->notify(new ResetPasswordNotification($url));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        //Validate input
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate the token
        $tokenData = DB::table('password_reset_tokens')->where('token', $request->token)->first();

        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return response()->json(['success' => false, 'error' => 'Invalid link, Please verify!',]);
        $user = User::where('email', $tokenData->email)->first();

        // Redirect the user back if the email is invalid
        if (!$user) return response()->json(['success' => false, 'error' => 'User not found',]);

        //Hash and update the new password
        $user->password = $request->password;
        $user->update(); //or $user->save();
        $user->tokens()->delete();

        //Delete the token
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        return response()->json(['success' => true, 'message' => 'Password changed. Please Login!',]);
    }
}
