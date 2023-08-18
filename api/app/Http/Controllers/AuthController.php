<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

// ...

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // Si les paramètres ne conviennent un message d'erreur est renvoyé
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['errors' => 'Wrong credentials'],401);
        }

        $password = $request->password;
        if (!Hash::check($password, $user->password)){
            return response()->json(['errors' => 'Wrong credentials'], 403);
        }

        $token = $user->remember_token;

        return response()->json(['accessToken' => $token,"user"=>$user], 202);

    // système d'authentification de Laravel pour connecter l'utilisateur
        $user = Auth::user();

   // Si le rôle n'est ni admin ni client, renvoyez une réponse par défaut
        return response()->json(['message' => 'Unknown role'], 403);
        }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
