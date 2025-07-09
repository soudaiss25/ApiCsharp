<?php

namespace App\Http\Controllers\Api;

use App\Models\Personne;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index()
    {
        // On récupère les users avec leur personne liée
        $users = User::with('personne')->get();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with('personne')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Validation des données
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:20',
            'identifiant' => 'required|string|max:20|unique:users',
            'mot_de_passe' => 'required|string|min:6',
            'status' => 'nullable|string|max:20',
            'role' => 'required|string|max:20',
        ]);

        // Crée d'abord la personne
        $personne = Personne::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'] ?? null,
            'telephone' => $data['telephone'] ?? '',
            'cni' => $data['cni'] ?? null,
        ]);

        // Puis le user lié
        $user = User::create([
            'id_personne' => $personne->id_personne,
            'identifiant' => $data['identifiant'],
            'mot_de_passe' => Hash::make($data['mot_de_passe']),
            'status' => $data['status'] ?? null,
            'role' => $data['role'],
        ]);

        return response()->json($user->load('personne'), 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::with('personne')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:20',
            'identifiant' => 'sometimes|string|max:20|unique:users,identifiant,' . $user->id,
            'mot_de_passe' => 'nullable|string|min:6',
            'status' => 'nullable|string|max:20',
            'role' => 'sometimes|string|max:20',
        ]);

        // Met à jour les infos de la personne liée
        $user->personne->update([
            'nom' => $data['nom'] ?? $user->personne->nom,
            'prenom' => $data['prenom'] ?? $user->personne->prenom,
            'email' => $data['email'] ?? $user->personne->email,
            'telephone' => $data['telephone'] ?? $user->personne->telephone,
            'cni' => $data['cni'] ?? $user->personne->cni,
        ]);

        // Met à jour les infos du user
        if (isset($data['mot_de_passe'])) {
            $data['mot_de_passe'] = Hash::make($data['mot_de_passe']);
        } else {
            unset($data['mot_de_passe']);
        }

        $user->update($data);

        return response()->json($user->load('personne'));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Supprimer la personne liée (grâce au cascade onDelete dans ta migration)
        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    public function setupAdmin()
    {
        // Vérifie s’il existe déjà un admin
        $adminExists = User::where('role', 'admin')->exists();

        if ($adminExists) {
            return response()->json(['message' => 'Admin déjà existant']);
        }

        // Étape 1 : Créer la personne
        $personne = Personne::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => 'admin@app.com',
            'telephone' => '770000000',
            'cni' => '0000000000'
        ]);

        // Étape 2 : Créer l'utilisateur avec la clé étrangère id_personne
        $user = User::create([
            'id_personne' => $personne->id_personne,
            'identifiant' => 'admin',
            'mot_de_passe' => bcrypt('admin123'),
            'status' => 'actif',
            'role' => 'admin',
        ]);

        return response()->json([
            'message' => 'Admin créé avec succès',
            'user' => $user
        ], 201);
    }
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'mot_de_passe' => 'required|string|min:6',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $user->mot_de_passe = Hash::make($request->mot_de_passe);
        $user->status = "actif";
        $user->save();

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès',
            'user' => $user
        ]);
    }


}
