<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::post('test', function (Request $r) {
    $r->validate([
        'name' => 'required'
    ]);
});

Route::group(['prefix' => 'v1'], function () {
    // login with socialite
    Route::post('login/{provider}', [SocialAuthController::class, 'redirectToProvider']);
    Route::get('login/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

    /* ================================== auth API starts ==================================*/
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
//    route::post('verify', [AuthController::class, 'verifyEmail'])->name('email.verify');
    route::post('reset-password-mail', [AuthController::class, 'resetPasswordMail'])->name('reset.password.mail');
//    route::post('resend/verification-code', [AuthController::class, 'resendVerifyCode'])->name('code.resend');
    Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.reset.submit');
    /* ================================== auth API ends ==================================*/

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('user/logout', [AuthController::class, 'logout']);

        /* ==========user profile api=========== */
        Route::get('user/info', [ProfileController::class, 'myInfo']);
        Route::post('/profile', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        /* =========end user profile api========== */

    });

});


