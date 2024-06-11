<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appSettingData = config('constant.APP_SETTINGS_SEEDER');
        foreach($appSettingData as $prefix => $appData){
            foreach($appData as $key => $value){
                AppSetting::updateOrCreate(
                    ['key' => $prefix.'_'. $key]  // seed only keys
                    ,['value' => $value]
                );
            }
        }
        
    }
}
