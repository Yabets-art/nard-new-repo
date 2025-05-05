<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    // Get all cart items for a specific user
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $items = CartItem::where('cart_id', $cart->id)->get();

        return response()->json($items);
    }

    // Add an item to the cart
    public function addItem(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'product_name' => 'required|string',
                'price' => 'required|numeric',
                'quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation failed', 'details' => $validator->errors()], 422);
            }

            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            $item = new CartItem([
                'product_name' => $request->product_name,
                'price' => $request->price,
                'quantity' => $request->quantity,
            ]);

            $cart->items()->save($item);

            return response()->json(['message' => 'Item added to cart successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add item to cart: ' . $e->getMessage()], 500);
        }
    }

    // Remove an item from the cart
    public function removeItem(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            $request->validate([
                'item_id' => 'required|exists:cart_items,id',
            ]);
            
            $item = CartItem::findOrFail($request->item_id);
            
            // Verify item belongs to user's cart
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart || $item->cart_id != $cart->id) {
                return response()->json(['error' => 'Item not found in user\'s cart'], 403);
            }
            
            $item->delete();
            
            return response()->json(['message' => 'Item removed from cart successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove item: ' . $e->getMessage()], 500);
        }
    }

    // Clear the entire cart
    public function clearCart(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
            return response()->json(['message' => 'Cart cleared successfully.']);
        }

        return response()->json(['message' => 'Cart not found.'], 404);
    }

    // Calculate total price
    public function getTotal(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['total' => 0]);
        }
        $total = $cart->items()->selectRaw('SUM(price * quantity) as total')->first()->total;

        return response()->json(['total' => $total]);
    }

    // Update quantity of an item
    public function updateQuantity(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            
            $request->validate([
                'item_id' => 'required|exists:cart_items,id',
                'quantity' => 'required|integer|min:1',
            ]);
            
            $item = CartItem::findOrFail($request->item_id);
            
            // Verify item belongs to user's cart
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart || $item->cart_id != $cart->id) {
                return response()->json(['error' => 'Item not found in user\'s cart'], 403);
            }
            
            $item->quantity = $request->quantity;
            $item->save();
            
            return response()->json(['message' => 'Quantity updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update quantity: ' . $e->getMessage()], 500);
        }
    }
}
