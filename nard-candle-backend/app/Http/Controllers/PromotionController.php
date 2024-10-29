<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    // Display a listing of promotions
    public function index()
    {
        $promotions = Promotion::all();
        return view('admin.promotion', compact('promotions'));
    }

    // Store a new promotion
    public function store(Request $request)
    {
        Log::info($request->all());
    
        // Validate both image and video uploads
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media' => 'required|mimes:jpg,jpeg,png,mp4,mov,avi|max:10048', // Allow images and videos
        ]);
    
        // Store the uploaded file (image or video)
        $mediaPath = $request->file('media')->store('promotions', 'public');
    
        // Create the promotion with the uploaded media path
        Promotion::create([
            'title' => $request->title,
            'description' => $request->description,
            'media' => $mediaPath, // Store the media path in the database
            'is_selected' => false,
        ]);
    
        return redirect()->back()->with('success', 'Promotion added successfully!');
    }
    

    // Update an existing promotion
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'media' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($promotion->image);
            $imagePath = $request->file('image')->store('promotions', 'public');
            $promotion->image = $imagePath;
        }

        $promotion->update([
            'title' => $request->title,
            'description' => $request->description,
            'media' => $request->media,
        ]);

        return redirect()->back()->with('success', 'Promotion updated successfully!');
    }

    // Delete a promotion
    public function destroy(Promotion $promotion)
    {
        // Delete the image file from storage
        Storage::disk('public')->delete($promotion->image);
        $promotion->delete();

        return redirect()->back()->with('success', 'Promotion deleted successfully!');
    }

    // Toggle promotion status (implement if needed)
    public function toggleStatus(Request $request, Promotion $promotion)
    {
        $promotion->is_selected = !$promotion->is_selected;
        $promotion->save();

        return response()->json(['success' => true, 'status' => $promotion->is_selected]);
    }

    public function promotions()
    {
        $promotions = Promotion::all();
        return response()->json($promotions);
    }
}
