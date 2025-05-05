<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            Log::info('Profile update requested', ['user_id' => $user->id]);
            
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                'phone_number' => 'required|string|max:20',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Profile update validation failed', [
                    'user_id' => $user->id,
                    'errors' => $validator->errors()
                ]);
                
                return response()->json([
                    'error' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update the user
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->save();
            
            Log::info('Profile updated successfully', ['user_id' => $user->id]);
            
            // Return the updated user data
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to update profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 