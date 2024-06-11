<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\ResetEmailVerificationRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\PhoneVerificationRequest;
use App\Http\Requests\Api\Auth\PhoneVerifyRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetEmailVerifyRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\Auth\ResetPhoneVerificationRequest;
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


class VerificationController extends BaseController
{

    /**
     * resend otp for phone verification.
     */
    public function sendPhoneVerificationOtp(PhoneVerificationRequest $request): JsonResponse
    {
        $verificationService = resolve(VerificationService::class);
        $user = User::where('country_code', $request->country_code)->where('phone', $request->phone)->first();
        $otpSent = $verificationService->sendOtp('phone',$user);

        if ($otpSent['success']) {
            return $this->successResponse(201, $otpSent['message']);
        } else {
            return $this->errorResponse(400, $otpSent['message']);
        }
    }
    
    /**
     * email verification.
     */
    public function phoneVerification(PhoneVerifyRequest $request): JsonResponse
    {
        $user = User::where('country_code', $request->country_code)->where('phone', $request->phone)
        ->with(['latestSubscription.plan'])->activeStatus()->first();
        $success = true;
        $token = '';
        if (!empty($user->otp) && Hash::check($request->otp, $user->otp)) {
            $user->phone_verified_at = now();
            $user->otp = null;
            $user->save();
            $sanctumToken = $user->createToken(
                $user->email . '-AuthToken',
                ['*'],
                $request->remember ?
                    now()->addMonth() :
                    now()->addDay()
            );
        } else {
            $success = false;
        }
        
        $userData = [
            'first_name' => $user->first_name,
            'last_name'=>$user->last_name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'country_code'=>$user->country_code,
            'timezone'=>$user->timezone,
            'work_profile'=>$user->work_profile,
            'phone_verified_at'=>$user->phone_verified_at,
            'email_verified_at'=>$user->email_verified_at,
        ];
        if(isset($user->latestSubscription)){
            $userData['subscription']=[
                'status'=> $user->latestSubscription->is_active,
                'started_at'=> $user->latestSubscription->started_at,
                'expires_at'=> $user->latestSubscription->expires_at,
                'amount'=> $user->latestSubscription->amount,
                'plan'=>[
                    'name'=> $user->latestSubscription->plan->name,
                    'price'=> $user->latestSubscription->plan->price,
                    'frequency'=> $user->latestSubscription->plan->frequency,
                    'number_of_posts'=> $user->latestSubscription->plan->number_of_posts,
                ]
            ];
        }else{
            $userData['subscription']['status']=Subscription::INACTIVE;
            $userData['subscription']['plan']['name'] = 'No Plan Subscribed';
        }
        if ($success) {
            return $this->successResponse(201, 'Phone number verified successfully.', ['token' => $sanctumToken->plainTextToken, 'user' => $userData]);
        } else {
            return $this->errorResponse(400, 'Oops! The OTP entered is invalid. Please try again.', ['otp' => ['Oops! The OTP entered is invalid. Please try again.']]);
        }
    }

    /**
     * resend otp for email verification.
     */
    public function sendEmailVerificationOtp(ResetEmailVerificationRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $verificationService = resolve(VerificationService::class);
        $otpSent = $verificationService->sendOtp('email', $user);
        
        if ($otpSent['success']) {
            return $this->successResponse(201, $otpSent['message']);
        } else {
            return $this->errorResponse(400,$otpSent['message']);
        }
    }
    

    /**
     * email verification.
     */
    public function emailVerification(ResetEmailVerifyRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        $success = true;
        if (!empty($user->otp) && Hash::check($request->otp, $user->otp)) {
            $user->email_verified_at = now();
            $user->otp = null;
            $user->save();
        } else {
            $success = false;
        }

        if ($success) {
            return $this->successResponse(201, 'Email verified successfully');
        } else {
            return $this->errorResponse(400, 'Oops! The OTP entered is invalid. Please try again.', ['otp' => ['Oops! The OTP entered is invalid. Please try again.']]);
        }
    }
   
    
}
