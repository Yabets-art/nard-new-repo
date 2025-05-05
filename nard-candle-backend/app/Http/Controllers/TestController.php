<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function testOrderCreation()
    {
        try {
            // Create a test order
            $order = Order::create([
                'user_id' => 3, // Use an existing user ID
                'tx_ref' => 'test_order_' . uniqid(),
                'total_amount' => 13.99,
                'status' => 'pending',
                'order_items' => json_encode([
                    [
                        'product_name' => 'Citrus Sunrise',
                        'price' => 13.99,
                        'quantity' => 1
                    ]
                ]),
                'payment_method' => 'chapa',
                'customer_email' => 'test@example.com',
                'customer_name' => 'Test User',
                'customer_phone' => '0000000000',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test order created successfully',
                'order_id' => $order->id,
                'order' => $order
            ]);
        } catch (\Exception $e) {
            Log::error('Order creation test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Order creation failed',
                'message' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }
} 