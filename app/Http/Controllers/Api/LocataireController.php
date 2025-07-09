<?php

namespace App\Http\Controllers\Api;

use App\Models\Locataire;
use App\Models\Personne;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocataireController extends Controller
{
    /**
     * Liste tous les locataires avec les infos de la personne liée.
     */
    public function index()
    {
        return response()->json(Locataire::with('personne')->get(), 200);
    }

    /**
     * Crée un locataire et sa personne liée.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:20',
        ]);

        // Créer d'abord la personne
        $personne = Personne::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'] ?? null,
            'telephone' => $validated['telephone'] ?? '',
            'cni' => $validated['cni'] ?? null,
        ]);

        // Puis créer le locataire lié à cette personne
        $locataire = Locataire::create([
            'id_personne' => $personne->id_personne,
        ]);

        return response()->json($locataire->load('personne'), 201);
    }

    /**
     * Affiche un locataire spécifique avec ses informations personnelles.
     */
    public function show($id)
    {
        $locataire = Locataire::with('personne')->find($id);

        if (!$locataire) {
            return response()->json(['message' => 'Locataire non trouvé'], 404);
        }

        return response()->json($locataire);
    }

    /**
     * Met à jour les informations du locataire et de sa personne liée.
     */
    public function update(Request $request, $id)
    {
        $locataire = Locataire::with('personne')->find($id);

        if (!$locataire) {
            return response()->json(['message' => 'Locataire non trouvé'], 404);
        }

        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:20',
        ]);

        // Mise à jour de la personne liée
        $locataire->personne->update([
            'nom' => $data['nom'] ?? $locataire->personne->nom,
            'prenom' => $data['prenom'] ?? $locataire->personne->prenom,
            'email' => $data['email'] ?? $locataire->personne->email,
            'telephone' => $data['telephone'] ?? $locataire->personne->telephone,
            'cni' => $data['cni'] ?? $locataire->personne->cni,
        ]);

        return response()->json($locataire->load('personne'));
    }

    /**
     * Supprime un locataire (la personne liée sera aussi supprimée via cascade).
     */
    public function destroy($id)
    {
        $locataire = Locataire::find($id);

        if (!$locataire) {
            return response()->json(['message' => 'Locataire non trouvé'], 404);
        }

        $locataire->delete();
        return response()->json(['message' => 'Locataire supprimé avec succès']);
    }
}
