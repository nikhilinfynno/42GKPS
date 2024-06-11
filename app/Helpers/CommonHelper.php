<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CommonHelper
{
    public static function generateOtp(){
        $otp = mt_rand(100000, 999999); // Generate 6-digit OTP
        $otp = 123456; //TBD : remove when goes live
        $hashOtp = Hash::make($otp); // Hash the OTP before storing
        return ['otp' => $otp, 'hashOtp' => $hashOtp];
    }

    public static function getDateRange($status, $customRange = null)
    {
        // Get the current date
        $currentDate = Carbon::now();
         
        // Calculate the start and end dates for different time ranges
        switch ($status) {
            case 'current_month':
                $startDate = $currentDate->startOfMonth();
                $endDate = $currentDate->copy()->endOfMonth();
                break;
            case 'last_7_days':
                $startDate = $currentDate->copy()->subDays(6); // Start date is 7 days ago
                $endDate = $currentDate->copy(); // End date is today
                break;
            case 'last_30_days':
                $endDate = $currentDate->copy(); // End date is today
                $startDate = $currentDate->copy()->subDays(29); // Start date is 30 days ago
                break;
            // case 'custom':
            //     //custom start and end dates
            //     dd();
            //     $startDate = Carbon::parse('2023-01-01'); // Example: Start date is January 1, 2023
            //     $endDate = Carbon::parse('2023-12-31'); // Example: End date is December 31, 2023
            //     break;
            default:
                // default current week
                if(!empty($customRange)){
                    $dateRange = explode('to', $customRange);
                    $startDate = (isset($dateRange[0])) ? Carbon::parse($dateRange[0])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
                    $endDate = (isset($dateRange[1])) ? Carbon::parse($dateRange[1])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
                }else{
                    $startDate = $currentDate->copy()->startOfWeek();
                    $endDate = $currentDate->copy()->endOfWeek();
                }
                break;
        }
        $data =  ['startDate' => $startDate, 'endDate' => $endDate];
        return $data;
    }
    
    public static function getDateByUserTimezone($date, $timezone = null, $convertTimezone = false, $showTime = false){
        $timezone = $timezone ?? config('constant.DEFAULT_TIMEZONE');
        // return $date->timezone($timezone)->format('Y-m-d H:i:s');
        if($convertTimezone){
            $carbonTimestamp = Carbon::parse($date)->tz($timezone);
        }else{
            $carbonTimestamp = Carbon::parse($date);
        }
        // Format the day with the ordinal suffix
        $formattedDay = $carbonTimestamp->format('jS');
        // Format the month and year
        $formattedMonthYear = $carbonTimestamp->format('F, Y');
       
        // Concatenate the formatted parts
        $formattedTimestamp = $formattedDay . ' ' . $formattedMonthYear ;
        if ($showTime) {
            // Format the time
            $formattedTimestamp .= ' - ' . $carbonTimestamp->format('g:i A');
        }
        return
        $formattedTimestamp;
    }
    
    public static function getAmountWithCurrency($amount){
        return config('constant.DEFAULT_CURRENCY_SYMBOL') . number_format((float)$amount, 2, '.', '');
    }
    
    public static function getDayTypeOfCurrentUser(){
        $timezone = auth()->user()->timezone ?? config('constant.DEFAULT_TIMEZONE');
        $currentTime = Carbon::now($timezone);
        $dayType = '';
        if ($currentTime->hour >= 5 && $currentTime->hour < 12) {
            $dayType = 'Good Morning';
        } elseif (
            $currentTime->hour >= 12
            && $currentTime->hour < 17
        ) {
            $dayType = 'Good Noon';
        } else {
            $dayType = 'Good Evening';
        }
        return $dayType;
    }
    
    public static function generateCode($type,$number, $length = 6  ){
        $prefix = 'ME';
        
        if($type == 'family'){
            $prefix = 'FA';
        }
        $result = str_pad($number, $length, '0', STR_PAD_LEFT);
        return $prefix. $result;
        
    }
    public static function getHumanHeight(){
        $minFeet = 4;
        $maxFeet = 8;
        $maxInch = 11;
        $heightData = [];
        for ($feet = $minFeet; $feet <= $maxFeet; $feet++) {
            for ($inch = 0; $inch <= $maxInch; $inch++) {
                  $heightData[] = "{$feet}'{$inch}\"";
            }
        }
        return $heightData;
    }
    public static function generateRandomString($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }
}