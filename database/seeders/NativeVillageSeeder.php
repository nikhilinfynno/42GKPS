<?php

namespace Database\Seeders;

use App\Models\NativeVillage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class NativeVillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/nativeVillages.json'));
        $data = json_decode($json, true);
        $id = 0;
        foreach ($data['native_villages'] as $key => $value) {
            $id++;
            NativeVillage::updateOrCreate([
                'id' => $id,
            ], [
                'name' => $value
            ]);
        }
    }
}
