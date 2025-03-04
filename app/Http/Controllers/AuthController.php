<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // **User Registration**
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    // **User Login**
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate a simple token manually
        $token = $user->createToken('token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ])->cookie('token', $token, 60 * 24, '/', null, true, true); // Store token in HTTP-only secure cookie
    }

    // **User Logout**
    public function logout(Request $request)
    {
        $token = $request->cookie('token'); // Get token from cookie

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->remember_token = null; // Clear token on logout
        $user->save();

        return response()->json(['message' => 'Logged out successfully'])
            ->cookie('token', '', -1, '/', null, true, true); // Clear cookie by setting it to expire
    }
}
