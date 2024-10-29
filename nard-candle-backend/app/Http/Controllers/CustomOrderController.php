<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder; // Ensure you have a model for CustomOrder
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // Import the Mail facade
class CustomOrderController extends Controller
{
    // Show the custom orders
    public function index()
    {
        $customOrders = CustomOrder::orderBy('created_at', 'desc')->paginate(7); // Paginate 7 items per page
        return view('admin.custom-order', compact('customOrders'));
    }


    public function store(Request $request)
    {
        Log::info("request recieved: ", $request->all());
        // Validate input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'preferences' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image
        ]);

        // Handle the image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('custom_orders', 'public'); // Save image in storage
        }

        // Create and store the custom order
        $order = CustomOrder::create([
            'name' => $request->name,
            'contact_method' => $request->email,
            'preferences' => $request->preferences,
            'image' => $imagePath,
            'status' => 'pending',
        ]);
        Log::info('Order Image Path:', ['path' => $order->image]);

        return response()->json([
            'message' => 'Custom order created successfully',
            'order' => $order,
        ], 201);
    }

    // Accept the custom order
    public function accept($id)
    {
        $order = CustomOrder::findOrFail($id);
        $order->status = 'accepted';
        $order->save();

        // Send acceptance email
        try {
            Mail::to($order->contact_method)->send(new \App\Mail\OrderAcceptedMail($order));
        } catch (\Exception $e) {
            return redirect()->route('custom-orders.index')->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return redirect()->route('custom-orders.index')->with('success', 'Order accepted.');
    }

    // Mark the custom order as completed
    public function complete($id)
    {
        $order = CustomOrder::findOrFail($id);
        $order->status = 'completed';
        $order->save();


        try {
            Mail::to($order->contact_method)->send(new \App\Mail\OrderCompletedMail($order));
        } catch (\Exception $e) {
            return redirect()->route('custom-orders.index')->with('error', 'Failed to send email: ' . $e->getMessage());
        }
        // Send completion email


        return redirect()->route('custom-orders.index')->with('success', 'Order marked as completed.');
    }
}
