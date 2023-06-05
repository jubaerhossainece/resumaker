<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/something', function(){
    return 'hello';
});
Route::get(
    '/clear-cache',
    function () {

        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        Artisan::call('view:clear');
        Artisan::call('route:cache');
        return '<h1>Cache facade value cleared</h1>';
        // return vie')->with('<h1>Cache facade value cleared</h1>');
    }
);

Route::get('/test', [TestController::class, 'test']);

Route::get('/sym-link', function(){
    Artisan::call('storage:link');
    return response([
        'status' => true,
        'message' => 'Symbolic link created.'
    ]);
});

require __DIR__ . '/auth.php';

// Route::get('login/{provider}', [Api\SocialAuthController::class, 'redirectToProvider']);
// Route::get('login/{provider}/callback', [Api\SocialAuthController::class, 'handleProviderCallback']);

// Route::group(['middleware' => 'auth'], function () {
// });

Auth::routes();

Route::get('verify-google-2fa', [\App\Http\Controllers\Auth\TwoFactorAuthController::class,'verify2faPage'])->name('verify2faPage');
Route::post('verify-2fa-code', [\App\Http\Controllers\Auth\TwoFactorAuthController::class,'loginVerifyWith2fa'])->name('verifyCode');

Route::group(['middleware' => ['auth','2fa']],function(){
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/2fa-settings', [SettingController::class, 'index']);

    Route::get('/2fa-status-change/{status}', [SettingController::class,'enableOrDisable2fa'])->name('google2faStatusChange');
});

