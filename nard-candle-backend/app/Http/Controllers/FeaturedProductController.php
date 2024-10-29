<?php 

namespace App\Http\Controllers;

use App\Models\FeaturedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeaturedProductController extends Controller
{
    // Display a listing of featured products
    public function index()
    {
        $featuredProducts = FeaturedProduct::all();
        return view('admin.featuredproduct', compact('featuredProducts'));
    }

    // Store a new featured product
    public function store(Request $request)
{
    // Validate form inputs
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('featured_products', 'public'); // Save image in storage
        }

        // Create and store the custom order
        $featuredProduct = FeaturedProduct::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);

    return redirect()->back()->with('success', 'Product added successfully!');
}


    // Update an existing featured product
    public function update(Request $request, FeaturedProduct $featuredProduct)
{
    Log::info("update request: ", $request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Store the current image path to use if no new image is uploaded
    $imagePath = $featuredProduct->image;

    // Handle image upload if a new one is provided
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('featured_products', 'public'); // Save new image
    }

    // Update the product with the new name and the (new or existing) image path
    $featuredProduct->update([
        'name' => $request->name,
        'image' => $imagePath,
    ]);

    return redirect()->back()->with('success', 'Featured Product updated successfully!');
}


    // Delete a featured product
    public function destroy(FeaturedProduct $featuredProduct)
    {
        
        $featuredProduct->delete();
        return redirect()->back()->with('success', 'Featured Product deleted successfully!');
    }

    // Edit a featured product
    public function edit(FeaturedProduct $featuredProduct)
    {
        return response()->json($featuredProduct);
    }

    public function featured_products()
    {
        $featuredProducts = FeaturedProduct::all();
        return response()->json($featuredProducts);
    }

}
