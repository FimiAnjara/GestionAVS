<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\MvtCaisse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MvtCaisseController extends Controller
{
    // Affiche la liste des mouvements
    public function list()
    {
        $mouvements = MvtCaisse::with('caisse')->paginate(10);
        return view('mvt-caisse.list', compact('mouvements'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        $caisses = Caisse::all();
        return view('mvt-caisse.create', compact('caisses'));
    }

    // Stocke un nouveau mouvement
    public function store(Request $request)
    {
        $request->validate([
            'origine' => 'required|string|max:100',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'date_' => 'required|date',
            'id_caisse' => 'required|exists:caisse,id_caisse',
        ], [
            'origine.required' => 'L\'origine est requise',
            'date_.required' => 'La date est requise',
            'id_caisse.required' => 'La caisse est requise',
            'id_caisse.exists' => 'La caisse sélectionnée n\'existe pas',
        ]);

        // Générer automatiquement l'ID
        $id_mvt_caisse = 'MVT_' . strtoupper(uniqid());

        MvtCaisse::create([
            'id_mvt_caisse' => $id_mvt_caisse,
            'origine' => $request->origine,
            'debit' => $request->debit ?? 0,
            'credit' => $request->credit ?? 0,
            'description' => $request->description,
            'date_' => $request->date_,
            'id_caisse' => $request->id_caisse,
        ]);

        // Mettre à jour le solde de la caisse
        $caisse = Caisse::find($request->id_caisse);
        $solde = $caisse->montant + ($request->credit ?? 0) - ($request->debit ?? 0);
        $caisse->update(['montant' => $solde]);

        return redirect()->route('mvt-caisse.list')->with('success', 'Mouvement créé avec succès! (ID: ' . $id_mvt_caisse . ')');
    }

    // Affiche un mouvement spécifique
    public function show($id)
    {
        $mouvement = MvtCaisse::findOrFail($id);
        return view('mvt-caisse.show', compact('mouvement'));
    }

    // Affiche le formulaire d'édition
    public function edit($id)
    {
        $mouvement = MvtCaisse::findOrFail($id);
        $caisses = Caisse::all();
        return view('mvt-caisse.edit', compact('mouvement', 'caisses'));
    }

    // Met à jour un mouvement
    public function update(Request $request, $id)
    {
        $mouvement = MvtCaisse::findOrFail($id);
        
        $request->validate([
            'origine' => 'required|string|max:100',
            'debit' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'date_' => 'required|date',
            'id_caisse' => 'required|exists:caisse,id_caisse',
        ]);

        $mouvement->update([
            'origine' => $request->origine,
            'debit' => $request->debit ?? 0,
            'credit' => $request->credit ?? 0,
            'description' => $request->description,
            'date_' => $request->date_,
            'id_caisse' => $request->id_caisse,
        ]);

        return redirect()->route('mvt-caisse.show', $mouvement->id_mvt_caisse)->with('success', 'Mouvement mis à jour avec succès!');
    }

    // Supprime un mouvement
    public function destroy($id)
    {
        $mouvement = MvtCaisse::findOrFail($id);
        $mouvement->delete();
        return redirect()->route('mvt-caisse.list')->with('success', 'Mouvement supprimé avec succès!');
    }

    // Affiche l'état des caisses
    public function etat()
    {
        $caisses = Caisse::all();
        $totalCaisses = $caisses->sum('montant');
        
        $mouvementsTotal = MvtCaisse::selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')->first();
        
        return view('mvt-caisse.etat', compact('caisses', 'totalCaisses', 'mouvementsTotal'));
    }

    // Exporte l'état des caisses en PDF
    public function exportPdf()
    {
        $caisses = Caisse::all();
        $totalCaisses = $caisses->sum('montant');
        
        $mouvementsTotal = MvtCaisse::selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')->first();
        
        $data = compact('caisses', 'totalCaisses', 'mouvementsTotal');
        
        $pdf = \PDF::loadView('mvt-caisse.rapport-pdf', $data);
        
        return $pdf->download('rapport_financier_' . date('Y-m-d') . '.pdf');
    }
}
