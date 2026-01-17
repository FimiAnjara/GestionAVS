<?php

namespace App\Http\Controllers;

use App\Models\Unite;
use Illuminate\Http\Request;

class UniteController extends Controller
{
    public function index()
    {
        $unites = Unite::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('unites.list', compact('unites'));
    }

    public function create()
    {
        return view('unites.create');
    }

    public function edit($id)
    {
        $unite = Unite::findOrFail($id);
        return view('unites.edit', compact('unite'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:100|unique:unite,libelle',
        ]);

        try {
            $idUnite = 'UNI-' . time();
            
            Unite::create([
                'id_unite' => $idUnite,
                'libelle' => $validated['libelle'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Unité ajoutée avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'unité'
            ], 500);
        }
    }

    public function show($id)
    {
        $unite = Unite::findOrFail($id);
        return view('unites.show', compact('unite'));
    }

    public function update(Request $request, $id)
    {
        $unite = Unite::findOrFail($id);
        
        $validated = $request->validate([
            'libelle' => 'required|string|max:100|unique:unite,libelle,' . $id . ',id_unite',
        ]);

        try {
            $unite->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Unité modifiée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $unite = Unite::findOrFail($id);
            $unite->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Unité supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $unites = Unite::whereNull('deleted_at')
            ->where('libelle', 'like', "%{$search}%")
            ->get();
        
        return response()->json($unites);
    }
}
