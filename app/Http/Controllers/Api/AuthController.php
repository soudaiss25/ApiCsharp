<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifiant' => 'required|string',
            'mot_de_passe' => 'required|string',
        ]);

        // On récupère le user avec sa personne liée
        $user = User::with('personne')->where('identifiant', $credentials['identifiant'])->first();

        // Vérifie si l'utilisateur existe et que le mot de passe est bon
        if (!$user || !Hash::check($credentials['mot_de_passe'], $user->mot_de_passe)) {
            return response()->json([
                'message' => 'Identifiant ou mot de passe incorrect'
            ], 401);
        }

        // Vérifie si l'utilisateur a un rôle autorisé
        if (!in_array($user->role, ['admin', 'Gestionnaire'])) {
            return response()->json([
                'message' => 'Accès refusé : rôle non autorisé'
            ], 403);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user->id,
                'nom' => $user->personne->nom ?? null,
                'prenom' => $user->personne->prenom ?? null,
                'email' => $user->personne->email ?? null,
                'status' => $user->status,
                'role' => $user->role,
            ]
        ]);
    }
}
