<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email} {password} {firstName} {lastName}';

    protected $description = 'Create a new administrator user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $firstName = $this->argument('firstName');
        $lastName = $this->argument('lastName');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            // Update user to be admin
            $user = User::where('email', $email)->first();
            $user->is_admin = 1;
            $user->save();
            
            $this->info("User {$email} already exists. Updated to admin status.");
            return;
        }

        // Create new admin user
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'is_admin' => 1,
        ]);

        $this->info("Admin user created successfully: {$email}");
    }
} 