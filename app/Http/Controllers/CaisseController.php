<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\MvtCaisse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaisseController extends Controller
{
    // Affiche la liste des caisses avec pagination
    public function list()
    {
        $caisses = Caisse::paginate(10);
        return view('caisse.list', compact('caisses'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('caisse.create');
    }

    // Stocke une nouvelle caisse
    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:100',
            'montant' => 'required|numeric|min:0',
        ], [
            'libelle.required' => 'Le libellé est requis',
            'libelle.max' => 'Le libellé ne doit pas dépasser 100 caractères',
            'montant.required' => 'Le montant est requis',
            'montant.numeric' => 'Le montant doit être un nombre',
        ]);

        // Générer automatiquement l'ID
        $id_caisse = 'CAISSE_' . strtoupper(uniqid());

        Caisse::create([
            'id_caisse' => $id_caisse,
            'libelle' => $request->libelle,
            'montant' => $request->montant,
        ]);

        return redirect()->route('caisse.list')->with('success', 'Caisse créée avec succès! (ID: ' . $id_caisse . ')');
    }

    // Affiche une caisse spécifique
    public function show($id)
    {
        $caisse = Caisse::findOrFail($id);
        $mouvements = $caisse->mvtCaisse()->paginate(10);
        return view('caisse.show', compact('caisse', 'mouvements'));
    }

    // Affiche le formulaire d'édition
    public function edit($id)
    {
        $caisse = Caisse::findOrFail($id);
        return view('caisse.edit', compact('caisse'));
    }

    // Met à jour une caisse
    public function update(Request $request, $id)
    {
        $caisse = Caisse::findOrFail($id);
        
        $request->validate([
            'libelle' => 'required|string|max:100',
            'montant' => 'required|numeric|min:0',
        ], [
            'libelle.required' => 'Le libellé est requis',
            'libelle.max' => 'Le libellé ne doit pas dépasser 100 caractères',
            'montant.required' => 'Le montant est requis',
            'montant.numeric' => 'Le montant doit être un nombre',
        ]);

        $caisse->update([
            'libelle' => $request->libelle,
            'montant' => $request->montant,
        ]);

        return redirect()->route('caisse.show', $caisse->id_caisse)->with('success', 'Caisse mise à jour avec succès!');
    }

    // Supprime une caisse
    public function destroy($id)
    {
        $caisse = Caisse::findOrFail($id);
        $caisse->delete();
        return redirect()->route('caisse.list')->with('success', 'Caisse supprimée avec succès!');
    }
}
