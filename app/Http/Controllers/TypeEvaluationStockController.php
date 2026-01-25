<?php

namespace App\Http\Controllers;

use App\Models\TypeEvaluationStock;
use Illuminate\Http\Request;

class TypeEvaluationStockController extends Controller
{
    public function index()
    {
        $types = TypeEvaluationStock::whereNull('deleted_at')->get();
        return view('type-evaluation-stock.list', compact('types'));
    }

    public function create()
    {
        return view('type-evaluation-stock.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_type_evaluation_stock' => 'required|string|max:50|unique:type_evaluation_stock,id_type_evaluation_stock',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            TypeEvaluationStock::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Type d\'évaluation ajouté avec succès'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout'
            ], 500);
        }
    }

    public function show($id)
    {
        $type = TypeEvaluationStock::findOrFail($id);
        return view('type-evaluation-stock.show', compact('type'));
    }

    public function edit($id)
    {
        $type = TypeEvaluationStock::findOrFail($id);
        return view('type-evaluation-stock.edit', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = TypeEvaluationStock::findOrFail($id);
        
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $type->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Type d\'évaluation modifié avec succès'
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
            $type = TypeEvaluationStock::findOrFail($id);
            $type->delete();
            return response()->json([
                'success' => true,
                'message' => 'Type d\'évaluation supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }
}
