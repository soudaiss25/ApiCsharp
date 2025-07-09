<?php

namespace App\Http\Controllers\Api;

use App\Models\Proprietaire;
use App\Models\Personne;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProprietaireController extends Controller
{
    /**
     * Liste tous les propriétaires avec leur personne liée.
     */
    public function index()
    {
        return response()->json(Proprietaire::with('personne')->get(), 200);
    }

    /**
     * Crée un nouveau propriétaire et sa personne liée.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'nullable|email|max:100',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:20',
            'ninea' => 'required|string|max:20',
            'rccm' => 'required|string|max:20',
        ]);

        // Étape 1 : création de la personne
        $personne = Personne::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'] ?? null,
            'telephone' => $validated['telephone'] ?? '',
            'cni' => $validated['cni'] ?? null,
        ]);

        // Étape 2 : création du propriétaire lié à cette personne
        $proprietaire = Proprietaire::create([
            'id_personne' => $personne->id_personne,
            'ninea' => $validated['ninea'],
            'rccm' => $validated['rccm'],
        ]);

        return response()->json($proprietaire->load('personne'), 201);
    }

    /**
     * Affiche les détails d’un propriétaire.
     */
    public function show($id)
    {
        $proprietaire = Proprietaire::with('personne')->find($id);

        if (!$proprietaire) {
            return response()->json(['message' => 'Propriétaire non trouvé'], 404);
        }

        return response()->json($proprietaire);
    }

    /**
     * Met à jour les informations d’un propriétaire et de sa personne liée.
     */
    public function update(Request $request, $id)
    {
        $proprietaire = Proprietaire::with('personne')->find($id);

        if (!$proprietaire) {
            return response()->json(['message' => 'Propriétaire non trouvé'], 404);
        }

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:50',
            'prenom' => 'sometimes|string|max:50',
            'email' => 'nullable|email|max:100',
            'telephone' => 'nullable|string|max:20',
            'cni' => 'nullable|string|max:20',
            'ninea' => 'sometimes|string|max:20',
            'rccm' => 'sometimes|string|max:20',
        ]);

        // Mise à jour des infos de la personne liée
        $proprietaire->personne->update([
            'nom' => $validated['nom'] ?? $proprietaire->personne->nom,
            'prenom' => $validated['prenom'] ?? $proprietaire->personne->prenom,
            'email' => $validated['email'] ?? $proprietaire->personne->email,
            'telephone' => $validated['telephone'] ?? $proprietaire->personne->telephone,
            'cni' => $validated['cni'] ?? $proprietaire->personne->cni,
        ]);

        // Mise à jour des infos du propriétaire
        $proprietaire->update([
            'ninea' => $validated['ninea'] ?? $proprietaire->ninea,
            'rccm' => $validated['rccm'] ?? $proprietaire->rccm,
        ]);

        return response()->json($proprietaire->load('personne'));
    }

    /**
     * Supprime un propriétaire (et sa personne via cascade).
     */
    public function destroy($id)
    {
        $proprietaire = Proprietaire::find($id);

        if (!$proprietaire) {
            return response()->json(['message' => 'Propriétaire non trouvé'], 404);
        }

        $proprietaire->delete();

        return response()->json(['message' => 'Propriétaire supprimé avec succès']);
    }
}
