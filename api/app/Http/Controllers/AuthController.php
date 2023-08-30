<?php

namespace App\Http\Controllers;

use App\Models\User;  // Import the User model to interact with the users table
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // Import the Auth facade to manage authentication
use Illuminate\Support\Facades\Hash;  // Import the Hash facade for password hashing
use Illuminate\Support\Facades\Validator;  // Import the Validator facade for data validation

class AuthController extends Controller
{
    // Logout method
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();  // Logout the authenticated user

        return response()->noContent();  // Return an empty response (204 No Content)
    }

    // Login method
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',  // Validation: email field required and must be in email format
            'password' => 'required',  // Validation: password field required
        ]);

        // If validation fails, return a response with validation errors
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();  // Find user by email
        if (!$user) {
            return response()->json(['errors' => 'Wrong credentials'], 401);  // Incorrect credentials
        }

        $password = $request->password;
        if (!Hash::check($password, $user->password)) {
            return response()->json(['errors' => 'Wrong credentials'], 403);  // Incorrect credentials
        }

        $token = $user->remember_token;  // Get the user's remember token

        return response()->json(['accessToken' => $token, "user" => $user], 202);  // Return the token and details of the authenticated user

        $user = Auth::user();  // Get the authenticated user using Laravel's authentication system

        return response()->json(['message' => 'Unknown role'], 403);  // If the role is neither admin nor client, return a forbidden response
    }

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
