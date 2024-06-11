<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\ApiLoginRequest;
use App\Http\Requests\Api\Auth\ResetEmailVerificationRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetEmailVerifyRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\EmailNotification;
use App\Services\VerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;


class AuthController extends BaseController
{
    /**
     * Register new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // $otpData = CommonHelper::generateOtp();

        // $user = User::create([
        //     'first_name' => $request->first_name,
        //     'last_name' => $request->last_name,
        //     'email' => $request->email,
        //     'country_code' => $request->country_code,
        //     'phone' => $request->phone,
        //     'timezone' => $request->timezone ?? null,
        //     'password' => Hash::make($request->password),
        //     'otp' => $otpData['hashOtp'],
        //     'work_profile' => ($request->work_profile) ? implode(',',$request->work_profile) : null
        // ]);


        // $verificationService = resolve(VerificationService::class);
        // // send OTP for phone verification
        // $otpSent = $verificationService->sendOtp('phone', $user);
        // return $this->successResponse(201, 'Registration successful. An OTP has been sent to your phone number: '. $user->country_code. $user->phone.'.');
        return $this->successResponse(201, 'This feature will be available soon.');
        
    }
    
    /**
     * Generate sanctum token on successful login
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        $user = User::where('email',$request->email)->activeStatus()->hof()->first();

        $request->authenticate($user);
        $sanctumToken = $user->createToken(
            $user->email . '-AuthToken',
            ['*'],
            // $request->remember ?
            //     now()->addMonth() :
            //     now()->addDay()
        );
        $userData = [
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country_code' => $user->country_code,
            
        ];
        
        return $this->successResponse(200, 'Login success.',['token' => $sanctumToken->plainTextToken, 'user'=> $userData]);
    }

    /**
     * Revoke token; only remove token that is used to perform logout (i.e. will not revoke all tokens)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logout success.'
        ]);
        return $this->successResponse(200, 'Logout success.');
    }

    /**
     * Handle password reset.
     */
    public function sendResetPasswordOtp(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->activeStatus()->hof()->first();
        
        $verificationService = resolve(VerificationService::class);
        // send OTP for address verification
        $otpSent = $verificationService->sendOtp('email', $user, 'forgot-password');
        return $this->successResponse(201, 'An OTP has been sent to your email address: ' . $user->email . '.');
       
    }
    
    /**
     * verify otp and update password.
     */
    public function verifyOtpUpdatePassword(UpdatePasswordRequest $request){
        $user = User::where('email', $request->email)->activeStatus()->hof()->first();
        $success = true;
        if (!empty($user->otp) && Hash::check($request->otp, $user->otp)) {
            $user->password = Hash::make($request->password);
            $user->otp = null;
            if (empty($user->phone_verified_at)) {
                $user->phone_verified_at = now();
            }
            $user->save();
        } else {
            $success = false;
        }

        if ($success) {
            return $this->successResponse(201, 'Password changed successfully.');
        } else {
            return $this->errorResponse(400, 'Invalid OTP.', ['otp' => ['Given OTP is invalid.']]);
        }
    }

 
}
