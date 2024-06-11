<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminForgotPasswordRequest;
use App\Models\User;
use App\Notifications\OtpNotification;
use App\Services\VerificationService;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function forgotPasswordRequest(Request $request)
    {
        return view('auth.reset_password');
    }
    
    public function forgotPasswordOtp(AdminForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $verificationService = resolve(VerificationService::class);
        // send OTP for email verification
        $otpSent = $verificationService->sendOtp('email', $user, 'forgot-password');
        return redirect()->back()->with(['success' => 'OTP send to your registered email address.']);
           
    }
}
