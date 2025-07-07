<?php

namespace App\Http\Controllers\Api;

use App\Models\Appartement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppartementController extends Controller
{
    public function index()
    {
        return Appartement::with('typeAppartement', 'proprietaire')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'adresse_appartement' => 'required|string|max:100',
            'surface' => 'nullable|numeric',
            'nombre_piece' => 'nullable|integer',
            'id_proprietaire' => 'nullable|exists:proprietaires,id_proprietaire',
            'id_type_appartement' => 'required|exists:type_appartements,id_type_appartement',
            'disponible' => 'required|boolean',
        ]);

        $appartement = Appartement::create($data);
        return response()->json($appartement, 201);
    }

    public function show($id)
    {
        return Appartement::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $appartement = Appartement::findOrFail($id);
        $appartement->update($request->all());

        return response()->json($appartement);
    }

    public function destroy($id)
    {
        Appartement::destroy($id);
        return response()->json(null, 204);
    }
}

