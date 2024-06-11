<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/states.json'));
        $data = json_decode($json, true);
        foreach ($data['states'] as $key => $value) {

            State::updateOrCreate([
                'id' => $value['id'],
            ], [
                'name' => $value['name'],
                'country_id' => $value['country_id']
            ]);
        }
    }
}
