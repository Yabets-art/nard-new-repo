<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show products list
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(7);
        return view('admin.product', compact('products'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,HEIC|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        $fullPath = 'storage/' . $imagePath;

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $fullPath,
            'category_id' => 1
        ]);

        return redirect()->route('admin.product')->with('success', 'Product added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit-product', compact('product'));
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg,HEIC|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        return redirect()->route('admin.product')->with('success', 'Product updated successfully!');
    }

    // Delete product
public function delete($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('admin.product')->with('success', 'Product deleted successfully!');
}

}
