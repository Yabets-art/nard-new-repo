<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get the user by email
$email = 'yabetsd29@gmail.com';
$user = User::where('email', $email)->first();

if ($user) {
    echo "User found: {$user->email}\n";
    echo "Current admin status: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
    
    // Update to admin using direct DB update to avoid model caching issues
    DB::table('users')->where('id', $user->id)->update(['is_admin' => 1]);
    
    // Verify the update
    $user = User::where('email', $email)->first();
    echo "Updated admin status: " . ($user->is_admin ? 'YES' : 'NO') . "\n";
} else {
    echo "User not found with email: {$email}\n";
    
    // List all users
    echo "\nAll users in the database:\n";
    $users = User::all();
    foreach ($users as $u) {
        echo "{$u->id}: {$u->email} (Admin: " . ($u->is_admin ? 'YES' : 'NO') . ")\n";
    }
} 