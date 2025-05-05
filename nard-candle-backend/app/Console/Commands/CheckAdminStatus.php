<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAdminStatus extends Command
{
    protected $signature = 'admin:check {email?}';

    protected $description = 'Check if a user is an admin or list all admin users';

    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            // Check specific user
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email {$email} not found.");
                return;
            }

            $isAdmin = (bool)$user->is_admin;
            $this->info("User: {$user->email}");
            $this->info("Admin status: " . ($isAdmin ? 'YES' : 'NO'));
            $this->info("First name: {$user->first_name}");
            $this->info("Last name: {$user->last_name}");
        } else {
            // List all users with admin status
            $users = User::all();
            
            if ($users->isEmpty()) {
                $this->info("No users found in the database.");
                return;
            }

            $headers = ['ID', 'Email', 'First Name', 'Last Name', 'Is Admin'];
            $data = [];

            foreach ($users as $user) {
                $data[] = [
                    $user->id,
                    $user->email,
                    $user->first_name,
                    $user->last_name,
                    $user->is_admin ? 'YES' : 'NO'
                ];
            }

            $this->table($headers, $data);
        }
    }
} 