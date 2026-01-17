<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('fournisseurs.list', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function edit($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
            'lieux' => 'required|string|max:50',
        ]);

        try {
            $idFournisseur = 'FOUR-' . time();
            
            Fournisseur::create([
                'id_fournisseur' => $idFournisseur,
                'nom' => $validated['nom'],
                'lieux' => $validated['lieux'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fournisseur ajouté avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du fournisseur'
            ], 500);
        }
    }

    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return view('fournisseurs.show', compact('fournisseur'));
    }

    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:250',
            'lieux' => 'required|string|max:50',
        ]);

        try {
            $fournisseur->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Fournisseur modifié avec succès'
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
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Fournisseur supprimé avec succès'
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
        
        $fournisseurs = Fournisseur::whereNull('deleted_at')
            ->where('nom', 'like', "%{$search}%")
            ->get();
        
        return response()->json($fournisseurs);
    }
}
