<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Notifications\EmailNotification;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class VerificationService
{
    // Main method to send OTP based on type
    public function sendOtp($type, $user, $verification_for =null)
    {
      
        if ($type === 'email') {
            return $this->sendOtpByEmail($user, $verification_for ?? 'email-verification');
        } elseif ($type === 'phone') {
            return $this->sendOtpByPhone($user, $verification_for ?? 'phone-verification');
        } else {
           return false;
        }
    }

    // Send OTP to an email address
    private function sendOtpByEmail($user, $verification_for)
    {
       
        $success = false;
        $message = '';
        $otp = $this->generateOtp($user);
        if($verification_for == 'forgot-password'){
            $content = [
                'subject' => 'Your OTP for Password Reset',
                'message' => [
                    'Your OTP for password reset is: ' . $otp,
                    'Do not share it with anyone.If you did not request a password reset, no further action is required.',
                    'Thank you for using our application!'
                ],
            ];
            $success = true;
        }else{
            if (empty($user->email_verified_at)) {
               
                $content = [
                    'subject' => 'Your OTP for Email verification',
                    'message' => [
                        'Your OTP for email verification is: ' . $otp,
                        'Do not share it with anyone.',
                        'Thank you for using our application!'
                    ],
                ];
            $success = true;
            } else {
                $message = 'Your email is already verified';
            }
        }
        
        if($success){
            Notification::route('mail', $user->email)->notify(new EmailNotification($content));
            $message = 'Your OTP has been sent to '. $user->email;
        }
        return ['success' => $success, 'message' => $message];
    }

     
    // Send OTP to a phone number
    private function sendOtpByPhone($user, $verification_for = 'phone-verification')
    {
        $success = false;
        $message = '';
        
        $otp = $this->generateOtp($user);
        if ($verification_for == 'forgot-password') {
            $content = [
                'message' => [
                    'Your OTP for password reset is: ' . $otp.'.'
                ],
            ];
            $success = true;
        } else {
            if (empty($user->phone_verified_at)) {
                $content = [
                    'message' => [
                        'Dear Learner, your OTP for '.config('app.name') .' is : ' . $otp . '. OTP with anyone for security reasons.'
                    ],
                ];
                $success = true;
            } else {
                $message = 'Your phone is already verified.';
            }
        }
        if ($success) {
           //TBD send sms code goes here
           try{

                $smsService = resolve(SMSService::class);
                $params = [
                    'otp' => $otp,
                    'action' => 'phone verification',
                ];
                $templateId = config('constant.OTP_TEMPLATE_ID');
                $response = $smsService->sendSms($user, $templateId, $params);
                $success = $response['success'];
                $message = $response['message'];
                // $authKey = '420594AeOTaJ2cBLB66268349P1'; 
                // $mobileNumber = 918866302774;
                //My                 
                // $templateId = '66265c88d6fc054056720074'; 
                // $authKey = '420578A7dvbQjNqJa666265d8eP1'; 
                
                // $client = new Client();
            //     $response = $client->request('POST', 'https://control.msg91.com/api/v5/otp', [
            //         // 'query' => [
            //         //     'template_id' => $templateId,
            //         //     'mobile' => $mobileNumber,
            //         //     'authkey' => $authKey,
            //         // ],
            //         'json' => [
            //             'template_id' => $templateId,
            //             'mobile' => $mobileNumber,
            //             'authkey' => $authKey,
            //             'otp' => $otp,
            //             'action' => 'phone verification',
            //         ],
            //         'headers' => [
            //             'Content-Type' => 'application/json',
            //         ],
            //         'timeout' => 30,
            //     ]);

            //    $statusCode = $response->getStatusCode();
            //     $body = $response->getBody()->getContents();

                // Handle response based on status code and body
                 
                // if ($statusCode === 200) {
                //     $success = true;
                //     $message = 'OTP send to '. $mobileNumber;
                // } else {
                //     $success = false;
                //     $message = "Error: " . $statusCode;
                //     // Handle error response
                // }
           }catch(Exception $e){
                $success= false;
                 $message = $e->getMessage();
           }
           
        }
        
        return ['success' => $success, 'message' => $message];
    }
    
    //Generate OTP and save to user table
    private function generateOtp($user){
        $otpData = CommonHelper::generateOtp();
        $user->otp = $otpData['hashOtp'];
        $user->save();
        //For Testing purpose it should be removed when goes live
        Log::info('Phone:'.$user->country_code.$user->phone.'-- OTP:'. $otpData['otp']);
        return $otpData['otp'];
    }
}
