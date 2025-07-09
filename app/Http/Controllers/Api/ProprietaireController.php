<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proprietaire;
use App\Models\Personne;

class ProprietaireController extends Controller
{
    public function index()
    {
        return response()->json(Proprietaire::with('personne')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_personne' => 'required|exists:personnes,id_personne',
            'ninea' => 'required|string|max:20',
            'rccm' => 'required|string|max:20',
        ]);

        $proprietaire = Proprietaire::create($request->all());

        return response()->json($proprietaire, 201);
    }

    public function show($id)
    {
        $proprietaire = Proprietaire::with('personne')->find($id);
        if (!$proprietaire) {
            return response()->json(['message' => 'Propriétaire non trouvé'], 404);
        }
        return response()->json($proprietaire);
    }

    public function update(Request $request, $id)
    {
        $proprietaire = Proprietaire::find($id);
        if (!$proprietaire) {
            return response()->json(['message' => 'Propriétaire non trouvé'], 404);
        }

        $request->validate([
            'id_personne' => 'sometimes|exists:personnes,id_personne',
            'ninea' => 'sometimes|string|max:20',
            'rccm' => 'sometimes|string|max:20',
        ]);

        $proprietaire->update($request->all());
        return response()->json($proprietaire);
    }

    public function destroy($id)
    {
        $proprietaire = Proprietaire::find($id);
        if (!$proprietaire) {
            return response()->json(['message' => 'Propriétaire non trouvé'], 404);
        }

        $proprietaire->delete();
        return response()->json(['message' => 'Suppression réussie']);
    }
}
