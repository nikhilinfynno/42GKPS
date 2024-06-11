<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@gmail.com';
    
        // Update or create the user
        User::updateOrCreate(
            ['email' => $email],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        // Retrieve the updated or newly created user
        $user = User::where('email', $email)->first();

        // Ensure the user is not null before proceeding
        if ($user) {
            // Create user detail row
            UserDetail::updateOrCreate(['user_id' => $user->id]);

            // Get the super admin role
            $role = Role::where('slug', Role::SUPER_ADMIN)->first();

            // Ensure the role is not null before proceeding
            if ($role) {
                // Assign super admin role
                UserRole::updateOrCreate(['user_id' => $user->id], ['role_id' => $role->id]);
            } else {
                // Handle if the super admin role is not found
                $this->command->error('Super admin role not found.');
            }
        } else {
            // Handle if the user is not found
            $this->command->error('User not found.');
        }
        
    }
}
