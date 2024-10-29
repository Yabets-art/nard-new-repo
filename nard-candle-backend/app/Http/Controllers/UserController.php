<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function login(Request $request)
    {
        Log::info("request: ", $request->all());
    $validated = $request->validate([
        "email" => "required|email",
        "password" => "required",
    ]);

    $user = User::where("email", $request->email)->first();

    return redirect(route('index'));
    }

    public function register(Request $request){
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|min:8",
        ]);
    }
}
