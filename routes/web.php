<?php

use App\Http\Controllers\AppSetting\AppSettingController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Location\LocationController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if(Auth::check()){
        return redirect('/dashboard');
    }else{
        return view('auth.login');
    }
});
// Route::group(['prefix' => 'password'],function () {
//         Route::post('request', [ForgotPasswordController::class,'forgotPasswordOtp'])->name('password.reset.request');
//         Route::get('request', [ForgotPasswordController::class,'forgotPasswordRequest'])->name('forgot.password.request');
//     }
// );
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('edit', 'edit')->name('edit.personal.profile');
        Route::put('update', 'update')->name('update.personal.profile');
    });
    
    Route::prefix('locations')->controller(LocationController::class)->group(function () {
        Route::get('countries/', 'getCountries')->name('location.countries');
        Route::get('states/{country_id?}', 'getStates')->name('location.states');
        Route::get('cities/{state_id?}', 'getCities')->name('location.cities');
    });
    Route::prefix('hofs')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('hof.index');
        Route::get('create', 'create')->name('hof.create');
        Route::get('{user}/edit', 'edit')->name('hof.edit');
        Route::post('store', 'store')->name('hof.store');
        Route::post('{user}/member', 'store')->name('hof.add.member');
        Route::put('{user}/update', 'update')->name('hof.update');
        Route::get('show/{user}', 'show')->name('hof.show');
        Route::delete('delete/{user}', 'destroy')->name('hof.destroy');
    });
    Route::prefix('members')->controller(UserController::class)->group(function () {
        Route::get('/', 'members')->name('members.index');
    });
    Route::prefix('app-settings')->controller(AppSettingController::class)->group(function () {
        Route::get('/', 'index')->name('app.setting.index');
        Route::post('/update', 'update')->name('user.setting.update');
       
    });
});
