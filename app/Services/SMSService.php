<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SMSService
{
      
    public function sendSms($user, $templateId, $data, $messages = [])
    {
        $success = false;
        $message = '';
            try{
                $client = new Client();
                $authKey = config('constant.MSG91_AUTH_KEY');
                $mobileNumber = str_replace('+','', $user->country_code. $user->phone);
                
                $response = $client->request('POST', 'https://control.msg91.com/api/v5/otp', [
                    'json' => [
                        ...$data, 
                        'authkey'=> $authKey, 
                        'template_id'=> $templateId, 
                        'mobile' => $mobileNumber,
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'timeout' => 30,
                ]);

                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                if ($statusCode === 200) {
                    $success = true;
                    $message = $messages['success'] ?? 'Sms sent to the '. $mobileNumber;
                } else {
                    $success = false;
                    $message = $messages['error'] ?? 'Something went wrong.';
                }
           }catch(Exception $e){
                $success= false;
                 $message = $e->getMessage();
           }
        return ['success' => $success, 'message' => $message];
    }
    
     
}
