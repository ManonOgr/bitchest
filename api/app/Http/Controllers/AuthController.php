<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Logout method
    public function logout(Request $request)
    {
        // Récupérez l'utilisateur authentifié


        // Vérifiez si l'utilisateur a un token actuel
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Retournez une réponse de succès
        return response()->json(['message' => 'Logout successful'], 200);
    }

    // Login method
    public function login(Request $request)
    {
        // Validation des données entrées par l'utilisateur
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',  // Validation: champ email requis et doit être au format email
            'password' => 'required',  // Validation: champ mot de passe requis
        ]);

        // Si la validation échoue, retournez une réponse avec les erreurs de validation
        if ($validate->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validate->errors()], 422);
        }

        // Tentative de connexion de l'utilisateur
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user(); // Récupérez l'utilisateur authentifié
            // $request->session()->regenerate();
            // Générez un token pour l'utilisateur connecté

            // Stockez le token dans la base de données (champ remember_token

            return response()->json(['user' => $user], 200);
        }

        // Si l'authentification échoue, retournez une réponse d'erreur
        return response()->json(['message' => 'Wrong credentials'], 401);
    }

    // Vos autres méthodes de contrôleur (index, store, show, update, destroy) peuvent rester inchangées
}
