<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/cities.json'));
        $data = json_decode($json, true);
        foreach ($data['cities'] as $value) {
            // added try catch as some of states may missing in json file
            try{
                City::updateOrCreate([
                    'id' => $value['id'],
                ], [
                    'name' => $value['name'],
                    'state_id' => $value['state_id']
                ]);
            }catch(\Exception $e){
                
            }
        }
    }
}
