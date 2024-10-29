<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // List all posts in JSON format for the frontend
    public function getPosts() {
        $posts = Post::all();
        return response()->json($posts);
    }
    // List all posts
    public function index() {
        $posts = Post::orderBy('created_at', 'desc')->paginate(7); // Order by created_at in descending order
        return view('admin.post', compact('posts'));
    }

    public function create() {
        return "create";
    }

    // Store new post
public function store(Request $request) {
    $request->validate([
        'title' => 'required',
        'short_description' => 'required',
        'link' => 'required|url',
        'media' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096', // Increased max size
        'author' => 'nullable|string',
    ]);

    $mediaPath = null;

    // Handle the image upload if provided
    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('uploads', $fileName, 'public');

        // Optional: Resize the image if needed
        // Image::make(storage_path('app/public/' . $filePath))->resize(800, null, function ($constraint) {
        //     $constraint->aspectRatio();
        //     $constraint->upsize();
        // })->save(storage_path('app/public/' . $filePath), 90); // Save at 90% quality

        $mediaPath = '/storage/' . $filePath; // Save the path to the 'media' field
    } else {
        // If no image is provided, use a higher-quality thumbnail from the video link
        $mediaPath = $this->getHighQualityThumbnailFromVideo($request->link);
    }

    Post::create([
        'title' => $request->title,
        'short_description' => $request->short_description,
        'link' => $request->link,
        'media' => $mediaPath,
        'author' => $request->author,
    ]);

    return redirect()->route('admin.post')->with('success', 'Post created successfully!');
}

// Function to extract a higher-quality thumbnail URL from the video link
private function getHighQualityThumbnailFromVideo($link) {
    if (preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/(watch\?v=)?([a-zA-Z0-9_-]{11})/', $link, $matches)) {
        $videoId = $matches[5];
        return "https://img.youtube.com/vi/$videoId/hqdefault.jpg"; // Use high-quality thumbnail URL
    }
    return null; // Return null or a default image if no thumbnail could be extracted
}

    

    // Edit post
    public function edit($id) {
        $post = Post::findOrFail($id);
        return view('admin.edit_post', compact('post'));
    }

    // Update post
    public function update(Request $request, $id) {
        $post = Post::findOrFail($id);
        $post->update([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.post')->with('success', 'Post updated successfully!');
    }

    // Delete post
    public function destroy($id) {
        Post::findOrFail($id)->delete();
        return redirect()->route('admin.post')->with('success', 'Post deleted successfully!');
    }
}
