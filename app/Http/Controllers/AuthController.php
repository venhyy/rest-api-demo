<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "message" => "User registered"
        ], 200);
    }

    public function login(Request $request)
    {

        $credentials = $request->validate(["email" => "required", "password" => "required"]);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                "message" => "Login failed"
            ], 401);
        }
        $user = User::where("email", $request->email)->first();
        return response()->json([
            "message" => "User authenticated",
            "token" => $user->createToken("API TOKEN")->plainTextToken
        ]);
    }
}
