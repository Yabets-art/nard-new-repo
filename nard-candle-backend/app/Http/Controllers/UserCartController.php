<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCart; // Assuming you have a UserCart model
use Illuminate\Support\Facades\Auth;

class UserCartController extends Controller
{
    // Display the cart items for the authenticated user
    public function index()
    {
        // Get the logged-in user's cart items
        $cartItems = UserCart::where('user_id', Auth::id())->with('product')->get();

        // Return the cart items as JSON or you can return a view if needed
        return response()->json($cartItems);
    }

    // Add a product to the user's cart
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create the cart item associated with the authenticated user
        $cartItem = UserCart::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        // Return success response
        return response()->json(['success' => 'Item added to cart!', 'cartItem' => $cartItem]);
    }

    // Update quantity or other details of a specific cart item
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find the cart item that belongs to the authenticated user
        $cartItem = UserCart::where('user_id', Auth::id())->findOrFail($id);

        // Update the cart item
        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json(['success' => 'Cart item updated!', 'cartItem' => $cartItem]);
    }

    // Remove an item from the user's cart
    public function destroy($id)
    {
        // Find and delete the cart item that belongs to the authenticated user
        $cartItem = UserCart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json(['success' => 'Item removed from cart']);
    }
}
