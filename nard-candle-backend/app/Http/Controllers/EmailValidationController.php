<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailValidationController extends Controller
{
    public function testEmailValidation(Request $request)
    {
        $email = $request->input('email', '');
        
        if (empty($email)) {
            return response()->json([
                'valid' => false,
                'message' => 'Email is empty'
            ]);
        }
        
        // First validate with PHP's built-in filter
        $phpValid = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        
        // Construct a minimal test payload for Chapa
        $testPayload = [
            'amount' => 10.00,
            'currency' => 'ETB',
            'email' => $email,
            'first_name' => 'Test',
            'last_name' => 'User',
            'tx_ref' => 'test_' . uniqid(),
        ];
        
        // Log the test attempt
        Log::info('Testing email validation', [
            'email' => $email,
            'php_valid' => $phpValid,
            'test_payload' => $testPayload
        ]);
        
        try {
            // Make a direct request to Chapa
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('CHAPA_SECRET_KEY')
            ])
            ->timeout(20)
            ->post('https://api.chapa.co/v1/transaction/initialize', $testPayload);
            
            $responseData = $response->json();
            $isValid = $response->successful();
            
            // Log the full response
            Log::info('Chapa validation response', [
                'status' => $response->status(),
                'body' => $responseData,
                'is_valid' => $isValid
            ]);
            
            if ($isValid) {
                return response()->json([
                    'valid' => true,
                    'message' => 'Email is valid for Chapa',
                    'details' => [
                        'php_valid' => $phpValid,
                        'chapa_response' => 'Accepted',
                        'checkout_url' => $responseData['data']['checkout_url'] ?? 'Not available'
                    ]
                ]);
            } else {
                $message = 'Email rejected by Chapa';
                
                // Try to extract specific validation errors
                if (isset($responseData['message']) && is_array($responseData['message'])) {
                    $errors = [];
                    foreach ($responseData['message'] as $field => $messages) {
                        if (is_array($messages)) {
                            $errors[] = ucfirst($field) . ': ' . implode(', ', $messages);
                        } else {
                            $errors[] = ucfirst($field) . ': ' . $messages;
                        }
                    }
                    if (!empty($errors)) {
                        $message = implode('. ', $errors);
                    }
                }
                
                return response()->json([
                    'valid' => false,
                    'message' => $message,
                    'details' => [
                        'php_valid' => $phpValid,
                        'response_code' => $response->status(),
                        'chapa_response' => $responseData
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception during email validation test', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'valid' => false,
                'message' => 'Error testing email with Chapa: ' . $e->getMessage(),
                'details' => [
                    'php_valid' => $phpValid
                ]
            ], 500);
        }
    }
} 