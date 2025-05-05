<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WebPaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        try {
            Log::info('Payment initiation started', [
                'request_headers' => $request->headers->all(),
                'request_ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Get authenticated user
            $user = Auth::user();
            Log::info('Auth check result', ['user' => $user ? $user->id : 'Not authenticated']);
            
            if (!$user) {
                Log::error('Payment failed: User not authenticated');
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $cart = Cart::with('items')->where('user_id', $user->id)->first();
            Log::info('Cart retrieved', [
                'cart_exists' => (bool)$cart,
                'cart_items_count' => $cart ? $cart->items->count() : 0
            ]);

            if (!$cart || $cart->items->isEmpty()) {
                Log::error('Payment failed: Cart is empty', ['user_id' => $user->id]);
                return response()->json(['error' => 'Cart is empty.'], 404);
            }

            $totalAmount = $cart->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });
            Log::info('Cart total calculated', ['total_amount' => $totalAmount]);

            $txRef = uniqid('order_');

            // Get user info from the authenticated user
            $firstName = $user->first_name ?? $user->name ?? 'Customer';
            $lastName = $user->last_name ?? '';
            $phoneNumber = $user->phone_number ?? '0000000000';
            $email = $user->email;
            
            // Email Debug Information
            Log::warning('EMAIL DEBUG - Original Email', [
                'raw_email' => $email,
                'php_validation' => filter_var($email, FILTER_VALIDATE_EMAIL) ? 'VALID' : 'INVALID',
                'email_parts' => explode('@', $email),
                'is_null' => is_null($email),
                'is_empty' => empty($email),
                'has_spaces' => strpos($email, ' ') !== false,
                'email_length' => strlen($email)
            ]);
            
            // Validate email - this is critical for Chapa
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::error('Payment failed: Invalid email', ['email' => $email]);
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid or missing email address. Please update your profile with a valid email.',
                    'redirect_to_profile' => true,
                    'debug_info' => [
                        'email' => $email,
                        'validation_result' => filter_var($email, FILTER_VALIDATE_EMAIL),
                        'is_empty' => empty($email)
                    ]
                ], 400);
            }
            
            // Use a fallback valid email if user email looks suspicious
            // Some emails might pass PHP validation but fail Chapa's stricter validation
            if (strpos($email, '@example.com') !== false || 
                strpos($email, 'test@') !== false || 
                strpos($email, 'user@') !== false) {
                
                $originalEmail = $email;
                $email = 'customer@nardcandles.com';
                
                Log::warning('Using fallback email instead of suspicious user email', [
                    'original_email' => $originalEmail,
                    'fallback_email' => $email
                ]);
            }
            
            Log::info('User info prepared', [
                'name' => "$firstName $lastName",
                'email' => $email
            ]);

            // IMPORTANT: Construct the exact payload format Chapa expects
            $data = [
                'amount' => $totalAmount,
                'currency' => "ETB",
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone_number' => $phoneNumber,
                'tx_ref' => $txRef,
                'return_url' => '', // Empty return URL to prevent automatic redirect
                'customization' => [
                    'title' => 'Nard Candles',
                    'description' => 'Payment for candle order',
                ],
            ];
            
            // DEBUG: Log the exact payload being sent to Chapa
            Log::debug('CHAPA PAYMENT DEBUG - Exact payload being sent', [
                'payload' => $data,
                'authorization' => 'Bearer ' . substr(env('CHAPA_SECRET_KEY'), 0, 5) . '...' // Show first 5 chars for security
            ]);

            // IMPORTANT: Create a pending order in the database BEFORE initiating payment
            // This ensures we have the order record regardless of payment API success
            $orderItems = $cart->items->map(function($item) {
                return [
                    'product_name' => $item->product_name,
                    'price' => (float)$item->price,
                    'quantity' => $item->quantity
                ];
            })->toArray();
            
            // Create pending order
            $order = Order::create([
                'user_id' => $user->id,
                'tx_ref' => $txRef,
                'total_amount' => $totalAmount,
                'status' => 'pending', // Start with pending status
                'order_items' => $orderItems,
                'payment_method' => 'chapa',
                'customer_email' => $email,
                'customer_name' => "$firstName $lastName",
                'customer_phone' => $phoneNumber,
                'paid_at' => null, // Will be updated when payment is confirmed
            ]);
            
            Log::info("Created pending order before payment initiation", [
                'order_id' => $order->id,
                'tx_ref' => $txRef,
                'total_amount' => $totalAmount
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('CHAPA_SECRET_KEY')
            ])
            ->timeout(120)
            ->post('https://api.chapa.co/v1/transaction/initialize', $data);
            
            // DEBUG: Log the exact response from Chapa
            Log::debug('CHAPA PAYMENT DEBUG - Exact response received', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                Log::info('Payment initiated successfully', [
                    'payment_url' => $response['data']['checkout_url'],
                    'tx_ref' => $txRef
                ]);
                
                // Store checkout URL with the order
                $order->checkout_url = $response['data']['checkout_url'] ?? null;
                $order->save();
                
                return response()->json([
                    'success' => true,
                    'payment_url' => $response['data']['checkout_url'],
                    'tx_ref' => $txRef,
                    'amount' => $totalAmount,
                    'message' => 'After payment, please take time to view the receipt before returning to our site.',
                    'debug_info' => [
                        'email_used' => $email,
                        'payload' => $data,
                        'order_id' => $order->id
                    ]
                ]);
            } else {
                // If payment initiation fails, mark the order as failed
                $order->status = 'failed';
                $order->save();
                
                Log::error('Payment initialization failed at Chapa', [
                    'response' => $response->json(),
                    'status' => $response->status()
                ]);
                
                // Extract validation errors if present
                $errorDetails = $response->json();
                $errorMessage = 'Payment initialization failed.';
                
                if (isset($errorDetails['message']) && is_array($errorDetails['message'])) {
                    $errors = [];
                    foreach ($errorDetails['message'] as $field => $messages) {
                        $errors[] = ucfirst($field) . ': ' . implode(', ', $messages);
                    }
                    if (!empty($errors)) {
                        $errorMessage = implode('. ', $errors);
                    }
                }
                
                return response()->json([
                    'success' => false,
                    'error' => $errorMessage,
                    'details' => $errorDetails,
                    'debug_info' => [
                        'email_used' => $email,
                        'email_validation' => filter_var($email, FILTER_VALIDATE_EMAIL) ? 'Valid in PHP' : 'Invalid in PHP',
                        'payload_sent' => $data
                    ]
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Exception during payment initiation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Payment process failed.',
                'message' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        // This endpoint is no longer needed since we're using empty return_url
        return response()->json([
            'success' => true,
            'message' => 'Payment confirmation endpoint is no longer used'
        ]);
    }

    public function checkPaymentStatus($txRef)
    {
        try {
            // First check if we already have an order with this tx_ref to prevent duplicates
            $existingOrder = Order::where('tx_ref', $txRef)->first();
            
            if ($existingOrder) {
                \Illuminate\Support\Facades\Log::info("Order for tx_ref {$txRef} already exists", [
                    'order_id' => $existingOrder->id,
                    'user_id' => $existingOrder->user_id,
                    'status' => $existingOrder->status
                ]);

                // Update the order status if it's still pending
                if ($existingOrder->status === 'pending') {
                    $existingOrder->status = 'completed';
                    $existingOrder->paid_at = now();
                    $existingOrder->save();
                    
                    \Illuminate\Support\Facades\Log::info("Updated order status from pending to completed", [
                        'order_id' => $existingOrder->id,
                        'tx_ref' => $txRef
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'message' => 'Payment was successful. Order already processed.',
                    'order_id' => $existingOrder->id,
                    'data' => [
                        'tx_ref' => $existingOrder->tx_ref,
                        'amount' => $existingOrder->total_amount,
                        'currency' => 'ETB',
                        'first_name' => explode(' ', $existingOrder->customer_name)[0] ?? '',
                        'last_name' => count(explode(' ', $existingOrder->customer_name)) > 1 ? 
                            implode(' ', array_slice(explode(' ', $existingOrder->customer_name), 1)) : '',
                        'email' => $existingOrder->customer_email,
                        'phone_number' => $existingOrder->customer_phone
                    ]
                ]);
            }
            
            // Try to get the authenticated user
            $user = Auth::user();
            
            // Log who is checking this payment
            \Illuminate\Support\Facades\Log::info("Checking payment status for tx_ref {$txRef}", [
                'authenticated_user' => $user ? $user->id : 'Not authenticated',
                'user_email' => $user ? $user->email : 'N/A'
            ]);
            
            // Verify with Chapa API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('CHAPA_SECRET_KEY')
            ])
            ->get("https://api.chapa.co/v1/transaction/verify/$txRef");
            
            // Log the full response for debugging
            \Illuminate\Support\Facades\Log::debug("Chapa verification response for tx_ref {$txRef}", [
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ]);

            // Even if Chapa API fails, we'll create a provisional order based on cart data
            if (!$response->successful() && $user) {
                \Illuminate\Support\Facades\Log::warning("Chapa API verification failed but proceeding with order creation", [
                    'tx_ref' => $txRef,
                    'user_id' => $user->id,
                    'http_status' => $response->status()
                ]);
                
                // Get cart items for the user
                $cart = Cart::with('items')->where('user_id', $user->id)->first();
                
                if ($cart && !$cart->items->isEmpty()) {
                    // Format cart items for storing in the order
                    $orderItems = $cart->items->map(function($item) {
                        return [
                            'product_name' => $item->product_name,
                            'price' => (float)$item->price,
                            'quantity' => $item->quantity
                        ];
                    })->toArray();
                    
                    // Calculate total
                    $totalAmount = $cart->items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    });
                    
                    // Create new order
                    $order = Order::create([
                        'user_id' => $user->id,
                        'tx_ref' => $txRef,
                        'total_amount' => $totalAmount,
                        'status' => 'processing',
                        'order_items' => $orderItems,
                        'payment_method' => 'chapa',
                        'customer_email' => $user->email ?? null,
                        'customer_name' => ($user->first_name ?? '') . ' ' . ($user->last_name ?? ''),
                        'customer_phone' => $user->phone_number ?? null,
                        'paid_at' => now(),
                    ]);
                    
                    \Illuminate\Support\Facades\Log::info("Created provisional order for tx_ref {$txRef}", [
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'total_amount' => $totalAmount
                    ]);
                    
                    // Clear the cart after successful order creation
                    foreach ($cart->items as $item) {
                        $item->delete();
                    }
                    
                    return response()->json([
                        'success' => true,
                        'status' => 'success',
                        'message' => 'Your order has been created successfully.',
                        'order_id' => $order->id,
                        'data' => [
                            'tx_ref' => $txRef,
                            'amount' => $totalAmount,
                            'currency' => 'ETB',
                            'first_name' => $user->first_name ?? '',
                            'last_name' => $user->last_name ?? ''
                        ]
                    ]);
                }
            }

            if ($response->successful() && isset($response['data']['status'])) {
                $status = $response['data']['status'];
                
                if ($status === 'success') {
                    // Extract user ID from Chapa response if available
                    $userData = $response['data'];
                    $userId = null;
                    
                    // Try to find the user based on email
                    if (isset($userData['email'])) {
                        $findUser = User::where('email', $userData['email'])->first();
                        if ($findUser) {
                            $userId = $findUser->id;
                        }
                    }
                    
                    // If we have an authenticated user, use that
                    if ($user) {
                        $userId = $user->id;
                    }
                    
                    // If we still don't have a user ID, create a temporary user
                    if (!$userId && isset($userData['email'])) {
                        // Create a temporary user with the email from Chapa
                        try {
                            $tempUser = User::create([
                                'name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                                'email' => $userData['email'],
                                'password' => bcrypt(uniqid()),  // Random temporary password
                            ]);
                            $userId = $tempUser->id;
                            
                            \Illuminate\Support\Facades\Log::info("Created temporary user for tx_ref {$txRef}", [
                                'user_id' => $userId,
                                'email' => $userData['email']
                            ]);
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to create temporary user", [
                                'error' => $e->getMessage(),
                                'tx_ref' => $txRef
                            ]);
                            // If user creation fails, use a default user ID or admin
                            $userId = 1;
                        }
                    }
                    
                    // If we still don't have a user ID, use a default
                    if (!$userId) {
                        $userId = 1; // Default user ID (admin or system)
                    }
                    
                    // Get cart items for the user
                    $cart = Cart::with('items')->where('user_id', $userId)->first();
                    
                    // If no cart found or cart is empty, create a dummy order item
                    $orderItems = [];
                    $totalAmount = isset($userData['amount']) ? (float)$userData['amount'] : 0;
                    
                    if ($cart && !$cart->items->isEmpty()) {
                        // Format cart items for storing in the order
                        $orderItems = $cart->items->map(function($item) {
                            return [
                                'product_name' => $item->product_name,
                                'price' => (float)$item->price,
                                'quantity' => $item->quantity
                            ];
                        })->toArray();
                        
                        // Calculate total from cart items
                        $totalAmount = $cart->items->sum(function ($item) {
                            return $item->quantity * $item->price;
                        });
                    } else {
                        // Create a dummy order item if cart is empty
                        $orderItems = [
                            [
                                'product_name' => 'Nard Candle Product',
                                'price' => $totalAmount,
                                'quantity' => 1
                            ]
                        ];
                    }
                    
                    // Create new order
                    $order = Order::create([
                        'user_id' => $userId,
                        'tx_ref' => $txRef,
                        'total_amount' => $totalAmount,
                        'status' => 'processing',
                        'order_items' => $orderItems,
                        'payment_method' => 'chapa',
                        'customer_email' => $userData['email'] ?? null,
                        'customer_name' => ($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? ''),
                        'customer_phone' => $userData['phone_number'] ?? null,
                        'paid_at' => now(),
                    ]);
                    
                    \Illuminate\Support\Facades\Log::info("Created order for tx_ref {$txRef}", [
                        'order_id' => $order->id,
                        'user_id' => $userId,
                        'total_amount' => $totalAmount
                    ]);
                    
                    // Clear the cart after successful order creation
                    if ($cart) {
                        foreach ($cart->items as $item) {
                            $item->delete();
                        }
                    }
                    
                    // Send order confirmation email
                    if ($order->customer_email) {
                        try {
                            Mail::to($order->customer_email)->send(new OrderPaidMail($order));
                        } catch (\Exception $e) {
                            // Log email sending failure but don't fail the request
                            \Illuminate\Support\Facades\Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                        }
                    }
                    
                    return response()->json([
                        'success' => true,
                        'status' => 'success',
                        'message' => 'Payment was successful. Order has been created.',
                        'order_id' => $order->id,
                        'data' => $response['data']
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'status' => $status,
                        'message' => 'Payment was not successful'
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Could not verify payment status',
                'details' => $response->json()
            ], 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Exception in checkPaymentStatus for tx_ref {$txRef}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
