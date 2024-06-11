<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'id' => 3,
                'name' => 'Member',
                'slug' => 'member',
            ],
        ];
        foreach($roles as $role){
            Role::updateOrCreate([
                'id' => $role['id']
            ],[
                'name' => $role['name'],
                'slug' => $role['slug'],
            ]);
        }
    }
}
