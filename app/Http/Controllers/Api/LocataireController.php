<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locataire;
use App\Models\Personne;

class LocataireController extends Controller
{
    public function index()
    {
        return response()->json(Locataire::with('personne')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_personne' => 'required|exists:personnes,id_personne',
        ]);

        $locataire = Locataire::create([
            'id_personne' => $request->id_personne,
        ]);

        return response()->json($locataire, 201);
    }

    public function show($id)
    {
        $locataire = Locataire::with('personne')->find($id);
        if (!$locataire) {
            return response()->json(['message' => 'Locataire non trouvé'], 404);
        }
        return response()->json($locataire);
    }

    public function update(Request $request, $id)
    {
        $locataire = Locataire::find($id);
        if (!$locataire) {
            return response()->json(['message' => 'Locataire non trouvé'], 404);
        }

        $request->validate([
            'id_personne' => 'required|exists:personnes,id_personne',
        ]);

        $locataire->update($request->all());
        return response()->json($locataire);
    }

    public function destroy($id)
    {
        $locataire = Locataire::find($id);
        if (!$locataire) {
            return response()->json(['message' => 'Locataire non trouvé'], 404);
        }

        $locataire->delete();
        return response()->json(['message' => 'Suppression réussie']);
    }
}
