<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function login(Request $request)
    {
        Log::info('Login attempt', ['email' => $request->email]);
        
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        Log::info('Credentials validated', ['email' => $credentials['email']]);

        // Check if user exists
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            Log::warning('User not found', ['email' => $credentials['email']]);
            return response()->json(['error' => 'User not found'], 404);
        }

        Log::info('User found', ['user_id' => $user->id]);

        // Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            Log::warning('Password verification failed', ['user_id' => $user->id]);
            return response()->json(['error' => 'Invalid password'], 401);
        }

        Log::info('Password verified successfully', ['user_id' => $user->id]);

        // Create token
        $token = $user->createToken('auth-token')->plainTextToken;
        Log::info('Token created', ['user_id' => $user->id]);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ],
            'token' => $token
        ]);
    }
}
