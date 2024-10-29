<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        Log::info("message request: " , $request->all());
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        Message::create($validatedData);

        return response()->json(['message' => 'Message sent successfully'], 200);
    }

    public function index()
    {
        // Fetch names and emails only
        $messages = Message::select('id', 'name', 'email')->get();
        return view('admin.message', compact('messages'));
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('admin.message-detail', compact('message'));
    }
}
