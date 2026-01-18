<?php

namespace App\Http\Controllers;

use App\Models\FactureFournisseur;
use App\Models\FactureFournisseurFille;
use App\Models\BonCommande;
use App\Models\Article;
use Illuminate\Http\Request;
use PDF;

class FactureFournisseurController extends Controller
{
    /**
     * Afficher la liste des factures fournisseur
     */
    public function list(Request $request)
    {
        $query = FactureFournisseur::query();
        
        if ($request->filled('date_from')) {
            $query->where('date_', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_', '<=', $request->date_to);
        }
        
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        
        if ($request->filled('id')) {
            $query->where('id_factureFournisseur', 'like', '%' . $request->id . '%');
        }
        
        $factures = $query->latest('date_')->paginate(10);
        
        return view('facture-fournisseur.list', compact('factures'));
    }

    /**
     * Créer une facture à partir d'un bon de commande
     * Si $id_bonCommande est null, affiche la liste des bons de commande disponibles
     */
    public function createFromBonCommande($id_bonCommande = null)
    {
        if ($id_bonCommande) {
            $bonCommande = BonCommande::with('proformaFournisseur.fournisseur', 'bonCommandeFille.article')->findOrFail($id_bonCommande);
            
            // Vérifier que le bon est en état 11
            if ($bonCommande->etat != 11) {
                return back()->withErrors(['etat' => 'Le bon de commande doit être en état "Validée par DG"']);
            }

            // Vérifier qu'une facture n'existe pas déjà
            if ($bonCommande->id_factureFournisseur) {
                return back()->withErrors(['facture' => 'Une facture existe déjà pour ce bon de commande']);
            }

            return view('facture-fournisseur.create', compact('bonCommande'));
        }
        
        // Si aucun ID n'est fourni, afficher la liste des bons de commande disponibles
        return view('facture-fournisseur.create', ['bonCommande' => null]);
    }

    /**
     * Stocker une nouvelle facture
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'id_bonCommande' => 'required|exists:bonCommande,id_bonCommande',
            'description' => 'nullable|string',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.quantite' => 'required|numeric|min:0.01',
            'articles.*.prix' => 'required|numeric|min:0',
        ]);

        $id = 'FACT_' . uniqid();
        $bonCommande = BonCommande::find($request->id_bonCommande);
        
        // Calculate total from submitted articles
        $montant_total = 0;
        foreach ($request->articles as $article) {
            $montant_total += $article['quantite'] * $article['prix'];
        }
        
        $facture = FactureFournisseur::create([
            'id_factureFournisseur' => $id,
            'date_' => $request->date_,
            'etat' => 1,
            'description' => $request->description,
            'id_bonCommande' => $request->id_bonCommande,
            'montant_total' => $montant_total,
            'montant_paye' => 0,
        ]);

        // Add submitted articles
        foreach ($request->articles as $index => $article) {
            FactureFournisseurFille::create([
                'id_factureFournisseurFille' => 'FACTF_' . uniqid(),
                'id_factureFournisseur' => $id,
                'id_article' => $article['id_article'],
                'quantite' => $article['quantite'],
                'prix_achat' => $article['prix'],
            ]);
        }

        // Update bon de commande
        $bonCommande->update(['id_factureFournisseur' => $id]);

        return redirect()->route('facture-fournisseur.show', $id)->with('success', 'Facture créée avec succès');
    }

    /**
     * Afficher les détails d'une facture
     */
    public function show($id)
    {
        $facture = FactureFournisseur::findOrFail($id);
        
        return view('facture-fournisseur.show', compact('facture'));
    }

    /**
     * Changer l'état d'une facture
     */
    public function changerEtat(Request $request, $id)
    {
        $facture = FactureFournisseur::findOrFail($id);
        
        $nouvelEtat = $request->input('etat');
        
        // Valider les transitions d'état
        if ($facture->etat == 1 && $nouvelEtat != 5 && $nouvelEtat != 0) {
            return back()->withErrors(['etat' => 'Transition d\'état non autorisée']);
        }
        if ($facture->etat == 5 && $nouvelEtat != 11 && $nouvelEtat != 0) {
            return back()->withErrors(['etat' => 'Transition d\'état non autorisée']);
        }
        
        $facture->update(['etat' => $nouvelEtat]);
        
        return back()->with('success', 'État de la facture mis à jour');
    }

    /**
     * Exporter en PDF
     */
    public function exportPdf($id)
    {
        $facture = FactureFournisseur::findOrFail($id);
        
        $pdf = PDF::loadView('facture-fournisseur.pdf', compact('facture'));
        return $pdf->download($facture->id_factureFournisseur . '.pdf');
    }

    /**
     * Supprimer
     */
    public function destroy($id)
    {
        $facture = FactureFournisseur::findOrFail($id);
        
        // Dénuder l'association avec le bon de commande
        if ($facture->bonCommande) {
            $facture->bonCommande->update(['id_factureFournisseur' => null]);
        }
        
        $facture->delete();
        
        return redirect()->route('facture-fournisseur.list')->with('success', 'Facture supprimée');
    }
}
