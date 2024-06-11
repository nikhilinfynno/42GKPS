<?php

namespace App\Http\Controllers\AppSetting;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppSettingRequest;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appSettings = AppSetting::all();
        $appSettingsData = [];
        foreach ($appSettings as $appSetting){
            switch ($appSetting->key) {
                case AppSetting::ANDROID_LATEST_VERSION:
                    $appSettingsData[AppSetting::ANDROID_LATEST_VERSION] = [
                        'value'=> $appSetting->value,
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::ANDROID_IS_FORCE_UPDATE:
                    $appSettingsData[AppSetting::ANDROID_IS_FORCE_UPDATE] = [
                        'value' => $appSetting->value,
                        'type' => 'radio',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::ANDROID_INFO:
                    $appSettingsData[AppSetting::ANDROID_INFO] = [
                        'value' => $appSetting->value,
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::ANDROID_APP_URL:
                    $appSettingsData[AppSetting::ANDROID_APP_URL] = [
                        'value' => urldecode($appSetting->value),
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::IOS_LATEST_VERSION:
                    $appSettingsData[AppSetting::IOS_LATEST_VERSION] = [
                        'value' => $appSetting->value,
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::IOS_IS_FORCE_UPDATE:
                    $appSettingsData[AppSetting::IOS_IS_FORCE_UPDATE] = [
                        'value' => $appSetting->value,
                        'type' => 'radio',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::IOS_APP_URL:
                    $appSettingsData[AppSetting::IOS_APP_URL] = [
                        'value' => urldecode($appSetting->value),
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::IOS_INFO:
                    $appSettingsData[AppSetting::IOS_INFO] = [
                        'value' => $appSetting->value,
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                case AppSetting::OTHER_HEADLINE_TEXT:
                    $appSettingsData[AppSetting::OTHER_HEADLINE_TEXT] = [
                        'value' => $appSetting->value,
                        'type' => 'textbox',
                        'label' => ucwords(str_replace('_', ' ', $appSetting->key))
                    ];
                    break;
                default:
                   
            }
        }
        return view('app-settings.form', compact('appSettingsData'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(AppSettingRequest $request)
    {
        $payload = $request->all();
        $keys = array_merge(AppSetting::ANDROID_SETTINGS, AppSetting::IOS_SETTINGS,AppSetting::OTHER_SETTINGS);
       
        foreach ($payload as $key => $value) {
            if(in_array($key,$keys)){
                // Check if the key already exists in the database
                $existingSetting = AppSetting::where('key', $key)->first();
                if(in_array($key,[AppSetting::IOS_APP_URL, AppSetting::ANDROID_APP_URL])){
                    $value = urlencode($value);
                }
                // Update existing setting
                $existingSetting->value = $value;
                $existingSetting->save();
            }
        }
        return redirect()->route('app.setting.index')->with('success', "App settings saved successfully.");
    }
 
}
