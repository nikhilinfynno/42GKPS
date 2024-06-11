<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Notifications\EmailNotification;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class EmailService
{
    // Send an email 
    public function sendPaymentLinkViaEmail($user,$link)
    {


        // $content = [
        //     'subject' => "Here's the payment link to top up your wallet.",
        //     'message' => [
        //     'Here is the payment link to top up your wallet.: <a href="'. $link.'">Click Here!</a>',
        //     'The link will expire in 15 minutes.',
        //         'Thank you for using our application!'
        //     ],
        // ];

        // Notification::route('mail', $user->email)->notify(new EmailNotification($content));

        $content = [
            'subject' => "Here's the payment link to top up your wallet.",
            'message' => [
                'Here is the payment link to top up your wallet: <a href="' . $link . '">Click Here!</a>',
                'The link will expire in 15 minutes.',
                'Thank you for using our application!'
            ],
        ];
        $htmlContent = implode('<br>', $content['message']);
        Mail::send([], [], function ($message) use ($user,
            $content
        ) {
            $message->to($user->email)
            ->subject($content['subject'])
            ->html(implode('<br>', $content['message']));
        });

        $message = 'Your payment link been sent to '. $user->email;
        return ['success' => true, 'message' => $message];
    }
     
}
