<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FreePostController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\UserPostController;
use App\Http\Controllers\Api\V1\VerificationController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Controllers\Api\V1\AppVersionController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Wallet\RazorPayWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return ['ok' => true, 'message' => 'Welcome to the API'];
});
Route::prefix('v1')->group(
    function () {
        
        Route::controller(AuthController::class)->group(
            function () {
            Route::post('login', 'login')->name('api.login');
            Route::post('register', 'register')->name('api.register');
            Route::post('forgot-password', 'sendResetPasswordOtp')->middleware('throttle:5,1')->name('api.forgot.password');
            Route::post('password/update', 'verifyOtpUpdatePassword')->middleware('throttle:5,1')->name('api.update.password');
            Route::get('work-profile', 'workProfile')->name('api.work-profile');
        });

        //TBD : App version API
        Route::get('app-version', [AppVersionController::class, 'index'])->name('api.app-version');

        Route::controller(VerificationController::class)->middleware('throttle:5,1')->group(
            function () {
                Route::post('email/verification/request',  'sendEmailVerificationOtp')->name('api.email.verification');
                Route::post('email/verify', 'emailVerification')->name('api.email.verify');
                Route::post('phone/verification/request', 'sendPhoneVerificationOtp')->name('api.phone.verification');
                Route::post('phone/verify', 'phoneVerification')->name('api.phone.verify');
        });
       
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');
            Route::prefix('user')->controller(ProfileController::class)->group(function () {
                Route::get('/', 'edit')->name('api.user');
                Route::post('profile/update', 'update')->name('api.profile.update');
            });
            Route::prefix('members')->controller(MemberController::class)->group(function () {
                Route::post('/', 'index')->name('api.members.index');
                Route::get('/{memberCode}', 'memberDetail')->name('api.members.detail');
                Route::get('family/{familyCode}', 'familyDetail')->name('api.family.detail');
            });
            Route::controller(MemberController::class)->group(function () {
                Route::get('/my-family', 'myFamily')->name('api.member.family');
                Route::get('/filters', 'filters')->name('api.member.filters');
            });
        });
    }
);


 