<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Store a new user
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'status' => 'required|in:client,admin',
        ], [
            'email.unique' => 'This email is already used by another user.',
        ]);

        // Create a new User instance and populate its attributes
        $user = new User();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']); // Hash the password
        $user->status = $validatedData['status'];
        $user->save();

        return response()->json(['message' => 'User added successfully']);
    }

    // Get all users
    public function getUsers()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email', 'status')->get();

        return response()->json($users);
    }

    public function getUserById($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    // Update user details
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate incoming request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id, 
            'status' => 'required|in:client,admin',
        ]);

        // Update user attributes with validated data
        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully']);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
