<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    
    const ANDROID_LATEST_VERSION = 'android_latest_version';
    const ANDROID_IS_FORCE_UPDATE = 'android_is_force_update';
    const ANDROID_INFO = 'android_info';
    const ANDROID_APP_URL = 'android_app_url';
    const IOS_LATEST_VERSION = 'ios_latest_version';
    const IOS_IS_FORCE_UPDATE = 'ios_is_force_update';
    const IOS_APP_URL = 'ios_app_url';
    const IOS_INFO = 'ios_info';
    const OTHER_HEADLINE_TEXT = 'other_headline_text';
    
    const ANDROID_SETTINGS = [
        self::ANDROID_LATEST_VERSION,
        self::ANDROID_IS_FORCE_UPDATE,
        self::ANDROID_INFO,
        self::ANDROID_APP_URL
    ];
    
    const IOS_SETTINGS = [
        self::IOS_LATEST_VERSION,
        self::IOS_IS_FORCE_UPDATE,
        self::IOS_APP_URL,
        self::IOS_INFO,
    ];
    
    const OTHER_SETTINGS = [
        self::OTHER_HEADLINE_TEXT,
    ];
    
}
