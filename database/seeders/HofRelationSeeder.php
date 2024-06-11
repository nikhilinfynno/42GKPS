<?php

namespace Database\Seeders;

use App\Models\HofRelation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HofRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/familyRelations.json'));
        $data = json_decode($json, true);
        $id = 0;
        foreach ($data['familyRelations'] as $value) {
            $id++;
            HofRelation::updateOrCreate([
                'id' => $id,
            ],[
                'title'=> $value,
            ]);
            
        }
    }
}
