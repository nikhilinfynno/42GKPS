<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/occupations.json'));
        $data = json_decode($json, true);
        $id = 0;
        foreach ($data['occupations'] as $key => $value) {
            $id++;
            Occupation::updateOrCreate([
                'id' => $id,
            ],[
                'name'=> $value
            ]);
            
        }
    }
}
