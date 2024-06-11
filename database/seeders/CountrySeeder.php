<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/countries.json'));
        $data = json_decode($json, true);
        foreach ($data['countries'] as $key => $value) {
            Country::updateOrCreate([
                'id' => $value['id'],
            ], [
                'short_name' => $value['sortname'],
                'name' => $value['name'],
                'phone_code' => $value['phoneCode']
            ]);
        }
    }
}
