<?php

namespace App\Http\Controllers;

use App\Models\YouTubeVideo;
use Illuminate\Http\Request;

class YouTubeVideoController extends Controller
{
    // Display a listing of YouTube videos
    public function index()
    {
        $videos = YouTubeVideo::all();
        return view('admin.youtube', compact('videos'));
    }

    // Store a new YouTube video
    public function store(Request $request)
{
    $request->validate([
        'link' => 'required|string',
        'description' => 'required|string|max:600',
    ]);

    // Extract video ID from the YouTube link
    $videoId = $this->extractYouTubeId($request->link);

    // Store the video with extracted video ID
    YouTubeVideo::create([
        'link' => $videoId,
        'description' => $request->description,
    ]);

    return redirect()->back()->with('success', 'YouTube Video added successfully!');
}


public function edit($id) {
    $video = YouTubeVideo::findOrFail($id);
    return response()->json($video); // Make sure to return the video as JSON
}

public function update(Request $request, $id)
{
    $video = YouTubeVideo::findOrFail($id);

    // Extract video ID from the YouTube link
    $videoId = $this->extractYouTubeId($request->link);

    $video->link = $videoId;
    $video->description = $request->description;
    $video->save();

    return redirect()->back()->with('success', 'YouTube Video updated successfully!');
}

    // Delete a YouTube video
    public function destroy($id) {
        $video = YouTubeVideo::findOrFail($id);
        $video->delete();
    
        return response()->json(['message' => 'Video deleted successfully!']);
    }
    public function show($id)
    {
        $video = YouTubeVideo::find($id);
    
        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }
    
        return response()->json($video);
    }
    
    // Fetch all YouTube videos as JSON for the API
    public function youtube_videos()
{
    return response()->json(YouTubeVideo::all());
}
private function extractYouTubeId($url)
{
    preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
    return $matches[1] ?? $url; // If match fails, return the original link (could handle this more strictly)
}

}
