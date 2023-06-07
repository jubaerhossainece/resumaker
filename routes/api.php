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

Route::get('v1/test', [TestController::class, 'test']);

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
    /* ================================== auth API ends ===================================*/

    /*==================================== Settings Api ==================================*/
    Route::get('/settings/advertisement', [SettingController::class, 'advertisement']);

    //personal info save
    Route::post('cv/personal-info', [Cv\PersonalInfoController::class, 'store']);
    Route::post('resume/personal-info', [Resume\PersonalInfoController::class, 'store']);

    Route::group(['middleware' => 'guestCheck'], function () {
        /*===========send pdf============*/
        Route::post('/send-pdf/mail', [SendPdfController::class, 'sendToMail']);
        /*===============================*/
        /*=========== CV Apis ===========*/
        /*===============================*/
        Route::group(['prefix' => 'cv/{id}'], function(){

            Route::get('/', [CvController::class, 'show']);

            /*================ change template ===============*/
            Route::post('/change-template', [Cv\TemplateController::class, 'change']);
            Route::post('/replace', [Cv\TemplateController::class, 'replace']);
            
            
            /*=========== personal info api =================*/
            Route::get('/personal-info', [Cv\PersonalInfoController::class, 'get']);
            Route::post('/personal-info/{personal_info}', [Cv\PersonalInfoController::class, 'update']);
    
            /*=========== experience api =================*/
            Route::get('/experiences', [Cv\ExperienceController::class, 'get']);
            Route::post('/experience', [Cv\ExperienceController::class, 'save']);
            Route::put('/experience/{experience}', [Cv\ExperienceController::class, 'update']);
            Route::delete('/experience/{experience}', [Cv\ExperienceController::class, 'destroy']);
    
            /*=========== education api =================*/
            Route::get('/education', [Cv\EducationController::class, 'get']);
            Route::post('/education', [Cv\EducationController::class, 'save']);
            Route::put('/education/{education}', [Cv\EducationController::class, 'update']);
            Route::delete('/education/{education}', [Cv\EducationController::class, 'destroy']);
    
            /*=========== api for skills section =================*/
            //skill
            Route::get('/skills', [Cv\SkillController::class, 'get']);
            Route::post('/skill', [Cv\SkillController::class, 'save']);
            Route::put('/skill/{skill}', [Cv\SkillController::class, 'update']);
            Route::delete('/skill/{skill}', [Cv\SkillController::class, 'destroy']);
    
            /*=========== certification api =================*/
            Route::get('/certifications', [Cv\CertificationController::class, 'get']);
            Route::post('/certification', [Cv\CertificationController::class, 'save']);
            Route::put('/certification/{certification}', [Cv\CertificationController::class, 'update']);
            Route::delete('/certification/{certification}', [Cv\CertificationController::class, 'destroy']);
    
            /*=========== award api =================*/
            Route::get('/awards', [Cv\AwardController::class, 'get']);
            Route::post('/award', [Cv\AwardController::class, 'save']);
            Route::put('/award/{award}', [Cv\AwardController::class, 'update']);
            Route::delete('/award/{award}', [Cv\AwardController::class, 'destroy']);
    
            /*=========== publications api =================*/
            Route::get('/publications', [Cv\PublicationController::class, 'get']);
            Route::post('/publication', [Cv\PublicationController::class, 'save']);
            Route::put('/publication/{publication}', [Cv\PublicationController::class, 'update']);
            Route::delete('/publication/{publication}', [Cv\PublicationController::class, 'destroy']);
    
            /*=========== references api =================*/
            Route::get('/references', [Cv\ReferenceController::class, 'get']);
            Route::post('/reference', [Cv\ReferenceController::class, 'save']);
            Route::put('/reference/{reference}', [Cv\ReferenceController::class, 'update']);
            Route::delete('/reference/{reference}', [Cv\ReferenceController::class, 'destroy']);
        });


        /*===============================*/
        /*=========== Resume Apis ===========*/
        /*===============================*/
        Route::group(['prefix' => 'resume/{id}'], function(){

            /*================ change template ===============*/
            Route::post('/change-template', [Resume\TemplateController::class, 'change']);
            Route::post('/replace', [Resume\TemplateController::class, 'replace']);

            /*=========== personal info api =================*/
            Route::get('personal-info', [Resume\PersonalInfoController::class, 'get']);
            Route::post('personal-info/{personal_info}', [Resume\PersonalInfoController::class, 'update']);

            /*=========== experience api =================*/
            Route::get('experiences', [Resume\ExperienceController::class, 'get']);
            Route::post('experience', [Resume\ExperienceController::class, 'save']);
            Route::put('experience/{experience}', [Resume\ExperienceController::class, 'update']);
            Route::delete('experience/{experience}', [Resume\ExperienceController::class, 'destroy']);

            /*=========== education api =================*/
            Route::get('education', [Resume\EducationController::class, 'get']);
            Route::post('education', [Resume\EducationController::class, 'save']);
            Route::put('education/{education}', [Resume\EducationController::class, 'update']);
            Route::delete('education/{education}', [Resume\EducationController::class, 'destroy']);

            /*=========== skill api =================*/
            //skill
            Route::get('skills', [Resume\SkillController::class, 'get']);
            Route::post('skill', [Resume\SkillController::class, 'save']);
            Route::put('skill/{skill}', [Resume\SkillController::class, 'update']);
            Route::delete('skill/{skill}', [Resume\SkillController::class, 'destroy']);
        });
        
    });


    Route::group(['middleware' => 'auth:sanctum'], function(){
        Route::post('user/logout', [AuthController::class, 'logout']);

        /* ==========user profile api=========== */
        Route::get('user/info', [ProfileController::class, 'myInfo']);
        Route::post('/profile', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        /* =========end user profile api========== */
    });

});


