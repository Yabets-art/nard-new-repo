<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPaidMail;

class OrderController extends Controller
{
    public function index()
    {
        return Order::all();
    }

    public function show($id)
    {
        return Order::findOrFail($id);
    }

    public function getUserOrders(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Try to find orders by user ID first
        $orders = Order::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        // If no orders found by user ID, try email as fallback
        if ($orders->isEmpty() && $user->email) {
            $orders = Order::where('customer_email', $user->email)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
            // Update user_id on these orders to properly associate them
            foreach ($orders as $order) {
                if (!$order->user_id || $order->user_id != $user->id) {
                    $order->user_id = $user->id;
                    $order->save();
                }
            }
        }
        
        // Log for debugging
        \Illuminate\Support\Facades\Log::info("Orders fetched for user {$user->id}", [
            'user_email' => $user->email,
            'order_count' => $orders->count(),
            'order_ids' => $orders->pluck('id')->toArray()
        ]);
        
        // Process order_items for all orders
        $orders = $orders->map(function($order) {
            // Process order_items field which is stored as JSON
            if ($order->order_items) {
                try {
                    if (is_string($order->order_items)) {
                        $order->order_items = json_decode($order->order_items, true);
                    }
                } catch (\Exception $e) {
                    // In case of JSON decode error, set to empty array
                    $order->order_items = [];
                }
            } else {
                $order->order_items = [];
            }
            
            return $order;
        });
                        
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $order = Order::create($request->all());
        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());
        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(null, 204);
    }

    public function checkOrderByTxRef($txRef)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Look for an order with this transaction reference
        $order = Order::where('tx_ref', $txRef)->first();
        
        // If no order found, return 404
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No order found with this transaction reference'
            ], 404);
        }
        
        // If order exists but not associated with this user, associate it now
        if ($order->user_id != $user->id) {
            // Check if email matches
            if ($order->customer_email == $user->email) {
                // Associate this order with the current user
                $order->user_id = $user->id;
                $order->save();
                
                \Illuminate\Support\Facades\Log::info("Order {$order->id} associated with user {$user->id}", [
                    'tx_ref' => $txRef,
                    'previous_user_id' => $order->getOriginal('user_id'),
                    'current_user_id' => $user->id
                ]);
            }
        }
        
        // Return the order details
        return response()->json([
            'success' => true,
            'message' => 'Order found',
            'order_id' => $order->id,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'tx_ref' => $order->tx_ref,
            'created_at' => $order->created_at
        ]);
    }

    /**
     * Update order status when viewing receipt
     */
    public function updateOrderStatusOnReceipt($txRef)
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        // Look for an order with this transaction reference
        $order = Order::where('tx_ref', $txRef)->first();
        
        // If no order found, return 404
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No order found with this transaction reference'
            ], 404);
        }
        
        // Only update if status is pending
        if ($order->status === 'pending') {
            // Update the order status to completed
            $order->status = 'completed';
            $order->paid_at = now();
            $order->save();
            
            // Log the status update
            \Illuminate\Support\Facades\Log::info("Order {$order->id} status updated from 'pending' to 'completed' via receipt view", [
                'tx_ref' => $txRef,
                'user_id' => $user->id
            ]);
            
            // Send order confirmation email
            try {
                Mail::to($order->customer_email)->send(new OrderPaidMail($order));
            } catch (\Exception $e) {
                // Log email sending failure but don't fail the request
                \Illuminate\Support\Facades\Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
        }
        
        // Return the updated order details
        return response()->json([
            'success' => true,
            'message' => $order->status === 'completed' ? 'Order marked as paid successfully' : 'Order status unchanged',
            'order_id' => $order->id,
            'status' => $order->status,
            'paid_at' => $order->paid_at
        ]);
    }
    
    /**
     * Display all orders in the admin checkin page
     */
    public function checkin()
    {
        // Fetch all orders from the database, newest first
        $orders = Order::orderBy('created_at', 'desc')->get();
        
        // Return the view with the orders data
        return view('admin.checkin', compact('orders'));
    }

    /**
     * Mark an order as completed from the admin panel
     */
    public function markAsCompleted($id)
    {
        // Find the order
        $order = Order::findOrFail($id);
        
        // Update the order status to completed
        $order->status = 'completed';
        
        // Set paid_at if not already set
        if (!$order->paid_at) {
            $order->paid_at = now();
        }
        
        $order->save();
        
        // Log the status update
        \Illuminate\Support\Facades\Log::info("Order {$order->id} status updated to 'completed' by admin", [
            'admin_id' => auth()->id(),
            'admin_email' => auth()->user()->email
        ]);
        
        // Send order confirmation email if not already sent
        if (!$order->getOriginal('status') == 'completed') {
            try {
                Mail::to($order->customer_email)->send(new OrderPaidMail($order));
            } catch (\Exception $e) {
                // Log email sending failure but don't fail the request
                \Illuminate\Support\Facades\Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
        }
        
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Order marked as completed successfully',
            'order_id' => $order->id,
            'status' => $order->status,
            'paid_at' => $order->paid_at
        ]);
    }
}
