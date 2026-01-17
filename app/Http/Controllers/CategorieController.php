<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('categories.list', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function edit($id)
    {
        $categorie = Categorie::findOrFail($id);
        return view('categories.edit', compact('categorie'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:250|unique:categorie,libelle',
        ]);

        try {
            $idCategorie = 'CAT-' . time();
            
            Categorie::create([
                'id_categorie' => $idCategorie,
                'libelle' => $validated['libelle'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie ajoutée avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de la catégorie'
            ], 500);
        }
    }

    public function show($id)
    {
        $categorie = Categorie::findOrFail($id);
        return view('categories.show', compact('categorie'));
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        
        $validated = $request->validate([
            'libelle' => 'required|string|max:250|unique:categorie,libelle,' . $id . ',id_categorie',
        ]);

        try {
            $categorie->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Catégorie modifiée avec succès'
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
            $categorie = Categorie::findOrFail($id);
            $categorie->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès'
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
        
        $categories = Categorie::whereNull('deleted_at')
            ->where('libelle', 'like', "%{$search}%")
            ->get();
        
        return response()->json($categories);
    }
}
