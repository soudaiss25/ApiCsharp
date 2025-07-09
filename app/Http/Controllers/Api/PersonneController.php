<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Personne;

class PersonneController extends Controller
{
    public function index()
    {
        return response()->json(Personne::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'email' => 'required|email|unique:personnes,email',
            'telephone' => 'required|string|max:20',
            'cni' => 'nullable|string|max:20',
        ]);

        $personne = Personne::create($request->all());

        return response()->json($personne, 201);
    }

    public function show($id)
    {
        $personne = Personne::find($id);
        if (!$personne) {
            return response()->json(['message' => 'Personne non trouvée'], 404);
        }
        return response()->json($personne);
    }

    public function update(Request $request, $id)
    {
        $personne = Personne::find($id);
        if (!$personne) {
            return response()->json(['message' => 'Personne non trouvée'], 404);
        }

        $request->validate([
            'nom' => 'sometimes|string|max:50',
            'prenom' => 'sometimes|string|max:50',
            'email' => 'sometimes|email|unique:personnes,email,' . $id . ',id_personne',
            'telephone' => 'sometimes|string|max:20',
            'cni' => 'nullable|string|max:20',
        ]);

        $personne->update($request->all());
        return response()->json($personne);
    }

    public function destroy($id)
    {
        $personne = Personne::find($id);
        if (!$personne) {
            return response()->json(['message' => 'Personne non trouvée'], 404);
        }

        $personne->delete();
        return response()->json(['message' => 'Suppression réussie']);
    }
}
