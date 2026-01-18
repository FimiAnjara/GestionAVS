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
    public function create(Request $request)
    {
        $caisses = Caisse::all();
        
        // Récupérer les paramètres passés depuis la facture
        $id_facture = $request->query('id_facture');
        $montant_reste = $request->query('montant');
        
        return view('mvt-caisse.create', compact('caisses', 'id_facture', 'montant_reste'));
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
            'id_factureFournisseur' => 'nullable|exists:factureFournisseur,id_factureFournisseur',
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

        // Si c'est un paiement de facture, mettre à jour le montant payé
        $id_factureFournisseur = null;
        if ($request->filled('id_factureFournisseur')) {
            $facture = \App\Models\FactureFournisseur::find($request->id_factureFournisseur);
            if ($facture) {
                $montant_paiement = $request->debit ?? 0; // Débit = sortie = paiement fournisseur
                $nouveau_montant_paye = min($facture->montant_paye + $montant_paiement, $facture->montant_total);
                $facture->update(['montant_paye' => $nouveau_montant_paye]);
                $id_factureFournisseur = $request->id_factureFournisseur;
            }
        }

        // Rediriger vers la facture si paiement, sinon vers la liste des mouvements
        if ($id_factureFournisseur) {
            return redirect()->route('facture-fournisseur.show', $id_factureFournisseur)->with('success', 'Paiement enregistré avec succès! (ID: ' . $id_mvt_caisse . ')');
        }

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

    // Crée un MvtCaisse à partir d'une FactureFournisseur
    public function createFromFacture($id_facture)
    {
        $facture = \App\Models\FactureFournisseur::findOrFail($id_facture);
        
        // Vérifier que la facture est en état 11
        if ($facture->etat != 11) {
            return back()->withErrors(['etat' => 'La facture doit être validée par DG (état 11)']);
        }

        $caisses = Caisse::all();
        
        $total = $facture->montant_total;
        $reste_a_payer = $facture->reste_a_payer;
        
        return view('mvt-caisse.create-from-facture', compact('facture', 'caisses', 'total', 'reste_a_payer'));
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
