<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paiement;

class PaiementController extends Controller
{
    /**
     * Liste tous les paiements avec leurs relations.
     */
    public function index()
    {
        $paiements = Paiement::with(['contratLocation', 'modePaiement'])->get();
        return response()->json($paiements, 200);
    }

    /**
     * Enregistre un nouveau paiement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_paiement' => 'required|date',
            'montant' => 'required|numeric|min:0',
            'numero_facture' => 'required|string|max:255',
            'id_contrat_location' => 'required|exists:contrat_locations,id_contrat_location',
            'id_mode_paiement' => 'required|exists:mode_paiements,id_mode_paiement',
        ]);

        $paiement = Paiement::create($validated);

        // Retour avec relations chargées
        return response()->json(
            Paiement::with(['contratLocation', 'modePaiement'])->find($paiement->id_paiement),
            201
        );
    }

    /**
     * Affiche un paiement spécifique.
     */
    public function show($id)
    {
        $paiement = Paiement::with(['contratLocation', 'modePaiement'])->find($id);

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        return response()->json($paiement);
    }

    /**
     * Met à jour un paiement.
     */
    public function update(Request $request, $id)
    {
        $paiement = Paiement::find($id);

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        $validated = $request->validate([
            'date_paiement' => 'sometimes|date',
            'montant' => 'sometimes|numeric|min:0',
            'numero_facture' => 'sometimes|string|max:255',
            'id_contrat_location' => 'sometimes|exists:contrat_locations,id_contrat_location',
            'id_mode_paiement' => 'sometimes|exists:mode_paiements,id_mode_paiement',
        ]);

        $paiement->update($validated);

        return response()->json(
            Paiement::with(['contratLocation', 'modePaiement'])->find($id)
        );
    }

    /**
     * Supprime un paiement.
     */
    public function destroy($id)
    {
        $paiement = Paiement::find($id);

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        $paiement->delete();
        return response()->json(['message' => 'Paiement supprimé avec succès']);
    }
}
