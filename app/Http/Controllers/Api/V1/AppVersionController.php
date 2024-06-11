<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppVersionController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $appSettings = AppSetting::all()->pluck('value','key')->toArray();
       
        $appVersionDetails = [];
        foreach($appSettings as $key => $value){
            // Split the key at the underscore character
            $keyParts = explode('_', $key, 2);
            $appVersionDetails[$keyParts[0]][$keyParts[1]] = $value;
        }
         
        return $this->successResponse(200, 'App version fetched successfully', $appVersionDetails);
    }

}
