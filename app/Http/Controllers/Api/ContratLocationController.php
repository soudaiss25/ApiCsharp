<?php

namespace App\Http\Controllers\Api;

use App\Models\ContratLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContratLocationController extends Controller
{
    /**
     * Lister tous les contrats avec les relations.
     */
    public function index()
    {
        $contrats = ContratLocation::with('appartement', 'locataire')->get();
        return response()->json($contrats);
    }

    /**
     * Créer un nouveau contrat.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'date_creation' => 'nullable|date',
            'id_appartement' => 'required|exists:appartements,id_appartement',
            'id_locataire' => 'required|exists:locataires,id',
        ]);

        $contrat = ContratLocation::create($validated);

        return response()->json($contrat->load('appartement', 'locataire'), 201);
    }

    /**
     * Afficher un contrat spécifique.
     */
    public function show($id)
    {
        $contrat = ContratLocation::with('appartement', 'locataire')->find($id);

        if (!$contrat) {
            return response()->json(['message' => 'Contrat non trouvé'], 404);
        }

        return response()->json($contrat);
    }

    /**
     * Mettre à jour un contrat existant.
     */
    public function update(Request $request, $id)
    {
        $contrat = ContratLocation::find($id);

        if (!$contrat) {
            return response()->json(['message' => 'Contrat non trouvé'], 404);
        }

        $validated = $request->validate([
            'numero' => 'sometimes|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
            'date_creation' => 'nullable|date',
            'id_appartement' => 'sometimes|exists:appartements,id_appartement',
            'id_locataire' => 'sometimes|exists:locataires,id',
        ]);

        $contrat->update($validated);

        return response()->json($contrat->load('appartement', 'locataire'));
    }

    /**
     * Supprimer un contrat.
     */
    public function destroy($id)
    {
        $contrat = ContratLocation::find($id);

        if (!$contrat) {
            return response()->json(['message' => 'Contrat non trouvé'], 404);
        }

        $contrat->delete();

        return response()->json(['message' => 'Contrat supprimé avec succès']);
    }
}
