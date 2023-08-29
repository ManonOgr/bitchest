<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'status' => 'required|in:client,admin',
        ], [
            'email.unique' => 'Cet e-mail est déjà utilisé par un autre utilisateur.',
            // Ajoutez des messages d'erreur pour d'autres validations si nécessaire
        ]);

        $user = new User();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']); // Hash the password
        $user->status = $validatedData['status'];
        $user->save();

        return response()->json(['message' => 'User added successfully']);
    }

    public function getUsers()
    {
        $users = User::select('id', 'first_name', 'last_name', 'email', 'status')->get();

        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:client,admin',
        ]);

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
