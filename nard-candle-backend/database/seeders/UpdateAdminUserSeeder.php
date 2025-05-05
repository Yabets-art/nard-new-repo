<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Update existing user to be admin (if exists)
        $userEmail = 'yabetsd29@gmail.com';
        $user = DB::table('users')->where('email', $userEmail)->first();
        
        if ($user) {
            DB::table('users')->where('id', $user->id)->update([
                'is_admin' => 1
            ]);
            $this->command->info("User $userEmail updated to admin status.");
        } else {
            // Create a new admin user if not exists
            DB::table('users')->insert([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => $userEmail,
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Created new admin user $userEmail with password 'admin123'.");
        }
        
        // Also ensure admin@admin.com has admin privileges
        $adminEmail = 'admin@admin.com';
        $adminUser = DB::table('users')->where('email', $adminEmail)->first();
        
        if ($adminUser) {
            DB::table('users')->where('id', $adminUser->id)->update([
                'is_admin' => 1
            ]);
            $this->command->info("User $adminEmail updated to admin status.");
        }
    }
} 