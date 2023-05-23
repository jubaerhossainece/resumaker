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
        
        /*===============================*/
        /*=========== CV Apis ===========*/
        /*===============================*/
        Route::get('/cv/{id}', [CvController::class, 'show']);

        /*=========== personal info api =================*/
        Route::get('cv/{id}/personal-info', [Cv\PersonalInfoController::class, 'get']);
        Route::post('cv/personal-info', [Cv\PersonalInfoController::class, 'store']);
        Route::put('cv/{id}/personal-info', [Cv\PersonalInfoController::class, 'update']);

        /*=========== experience api =================*/
        Route::get('cv/{id}/experience', [Cv\ExperienceController::class, 'get']);
        Route::post('cv/{id}/experience', [Cv\ExperienceController::class, 'save']);
        Route::put('cv/{id}/experience/{experience}', [Cv\ExperienceController::class, 'update']);
        Route::delete('cv/{id}/experience/{experience}', [Cv\ExperienceController::class, 'destroy']);

        /*=========== education api =================*/
        Route::get('cv/{id}/education', [Cv\EducationController::class, 'get']);
        Route::post('cv/{id}/education', [Cv\EducationController::class, 'save']);
        Route::put('cv/{id}/education/{education}', [Cv\EducationController::class, 'update']);
        Route::delete('cv/{id}/education/{education}', [Cv\EducationController::class, 'destroy']);

        /*=========== skill api =================*/
        Route::get('cv/{id}/skill', [Cv\SkillController::class, 'get']);
        Route::post('cv/{id}/skill', [Cv\SkillController::class, 'save']);

        /*=========== certification api =================*/
        Route::get('cv/{id}/certification', [Cv\CertificationController::class, 'get']);
        Route::post('cv/{id}/certification', [Cv\CertificationController::class, 'save']);
        Route::put('cv/{id}/certification/{certification}', [Cv\CertificationController::class, 'update']);
        Route::delete('cv/{id}/certification/{certification}', [Cv\CertificationController::class, 'destroy']);

        /*=========== award api =================*/
        Route::get('cv/{id}/awards', [Cv\AwardController::class, 'get']);
        Route::post('cv/{id}/award', [Cv\AwardController::class, 'save']);
        Route::put('cv/{id}/award/{award}', [Cv\AwardController::class, 'update']);
        Route::delete('cv/{id}/award/{award}', [Cv\AwardController::class, 'destroy']);

        /*=========== publications api =================*/
        Route::get('cv/{id}/publications', [Cv\PublicationController::class, 'get']);
        Route::post('cv/{id}/publication', [Cv\PublicationController::class, 'save']);
        Route::put('cv/{id}/publication/{publication}', [Cv\PublicationController::class, 'update']);
        Route::delete('cv/{id}/publication/{publication}', [Cv\PublicationController::class, 'destroy']);

        /*=========== references api =================*/
        Route::get('cv/{id}/references', [Cv\ReferenceController::class, 'get']);
        Route::post('cv/{id}/reference', [Cv\ReferenceController::class, 'save']);
        Route::put('cv/{id}/reference/{reference}', [Cv\ReferenceController::class, 'update']);
        Route::delete('cv/{id}/reference/{reference}', [Cv\ReferenceController::class, 'destroy']);
    });

});


