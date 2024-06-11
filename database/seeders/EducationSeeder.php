<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/educations.json'));
        $data = json_decode($json, true);
        $id = 0;
        foreach ($data['educations'] as $key => $value) {
            $id++;
            Education::updateOrCreate([
                'id' => $id,
            ], [
                'title' => $value
            ]);
        }
    }
}
