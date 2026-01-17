<?php

namespace App\Http\Controllers;

use App\Models\ProformaFournisseur;
use App\Models\ProformaFournisseurFille;
use App\Models\Fournisseur;
use App\Models\Article;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProformaFournisseurController extends Controller
{
    // Affiche la liste des proformas
    public function list(Request $request)
    {
        $query = ProformaFournisseur::with('fournisseur');
        
        // Recherche par fournisseur
        if ($request->filled('fournisseur')) {
            $query->where('id_fournisseur', $request->fournisseur);
        }
        
        // Recherche par date
        if ($request->filled('date_from')) {
            $query->whereDate('date_', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_', '<=', $request->date_to);
        }
        
        // Filtre par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        
        $proformas = $query->paginate(10);
        $fournisseurs = Fournisseur::all();
        
        return view('proforma-fournisseur.list', compact('proformas', 'fournisseurs'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        $fournisseurs = Fournisseur::all();
        $articles = Article::all();
        return view('proforma-fournisseur.create', compact('fournisseurs', 'articles'));
    }

    // Stocke une nouvelle proforma
    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'description' => 'nullable|string|max:500',
            'id_fournisseur' => 'required|exists:fournisseur,id_fournisseur',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.prix' => 'required|numeric|min:0',
        ], [
            'date_.required' => 'La date est requise',
            'id_fournisseur.required' => 'Le fournisseur est requis',
            'articles.required' => 'Au moins un article doit être ajouté',
            'articles.*.id_article.required' => 'L\'article est requis',
            'articles.*.quantite.required' => 'La quantité est requise',
            'articles.*.prix.required' => 'Le prix est requis',
        ]);

        // Générer automatiquement l'ID
        $id_proforma = 'PROF_' . strtoupper(uniqid());

        // Créer la proforma
        $proforma = ProformaFournisseur::create([
            'id_proformaFournisseur' => $id_proforma,
            'date_' => $request->date_,
            'description' => $request->description,
            'id_fournisseur' => $request->id_fournisseur,
            'etat' => 1, // Créée par défaut
        ]);

        // Ajouter les articles
        foreach ($request->articles as $article) {
            ProformaFournisseurFille::create([
                'id_proformaFornisseurFille' => 'PF_' . strtoupper(uniqid()),
                'prix_achat' => $article['prix'],
                'quantite' => $article['quantite'],
                'id_proformaFournisseur' => $id_proforma,
                'id_article' => $article['id_article'],
            ]);
        }

        return redirect()->route('proforma-fournisseur.list')->with('success', 'Proforma créée avec succès! (ID: ' . $id_proforma . ')');
    }

    // Affiche une proforma spécifique
    public function show($id)
    {
        $proforma = ProformaFournisseur::with('proformaFournisseurFille.article', 'fournisseur')->findOrFail($id);
        return view('proforma-fournisseur.show', compact('proforma'));
    }

    // Change l'état de la proforma
    public function changerEtat(Request $request, $id)
    {
        $proforma = ProformaFournisseur::findOrFail($id);
        
        $request->validate([
            'etat' => 'required|in:1,5,11,0',
        ]);

        $proforma->update(['etat' => $request->etat]);

        return redirect()->route('proforma-fournisseur.show', $id)->with('success', 'État de la proforma mis à jour!');
    }

    // Supprime une proforma
    public function destroy($id)
    {
        $proforma = ProformaFournisseur::findOrFail($id);
        $proforma->delete();
        return redirect()->route('proforma-fournisseur.list')->with('success', 'Proforma supprimée avec succès!');
    }

    // Exporte la proforma en PDF
    public function exportPdf($id)
    {
        $proforma = ProformaFournisseur::with('proformaFournisseurFille.article', 'fournisseur')->findOrFail($id);
        
        $totalMontant = $proforma->proformaFournisseurFille->sum(function($item) { 
            return ($item->quantite ?? 1) * ($item->prix_achat ?? 0); 
        });

        $pdf = Pdf::loadView('proforma-fournisseur.pdf', compact('proforma', 'totalMontant'));
        return $pdf->download('Proforma_' . $proforma->id_proformaFournisseur . '.pdf');
    }

    // Affiche le formulaire de modification
    public function edit($id)
    {
        $proforma = ProformaFournisseur::with('proformaFournisseurFille.article', 'fournisseur')->findOrFail($id);
        
        // Vérifier que l'état est Créée (1)
        if ($proforma->etat != 1) {
            return redirect()->route('proforma-fournisseur.show', $id)->with('error', 'Seules les proformas "Créées" peuvent être modifiées!');
        }

        $fournisseurs = Fournisseur::all();
        $articles = Article::all();
        return view('proforma-fournisseur.edit', compact('proforma', 'fournisseurs', 'articles'));
    }

    // Stocke les modifications
    public function update(Request $request, $id)
    {
        $proforma = ProformaFournisseur::findOrFail($id);
        
        // Vérifier que l'état est Créée (1)
        if ($proforma->etat != 1) {
            return redirect()->route('proforma-fournisseur.show', $id)->with('error', 'Seules les proformas "Créées" peuvent être modifiées!');
        }

        $request->validate([
            'date_' => 'required|date',
            'description' => 'nullable|string|max:500',
            'id_fournisseur' => 'required|exists:fournisseur,id_fournisseur',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.prix' => 'required|numeric|min:0',
        ]);

        // Mettre à jour la proforma
        $proforma->update([
            'date_' => $request->date_,
            'description' => $request->description,
            'id_fournisseur' => $request->id_fournisseur,
        ]);

        // Supprimer les anciens articles
        ProformaFournisseurFille::where('id_proformaFournisseur', $id)->delete();

        // Ajouter les nouveaux articles
        foreach ($request->articles as $article) {
            ProformaFournisseurFille::create([
                'id_proformaFornisseurFille' => 'PF_' . strtoupper(uniqid()),
                'prix_achat' => $article['prix'],
                'quantite' => $article['quantite'],
                'id_proformaFournisseur' => $id,
                'id_article' => $article['id_article'],
            ]);
        }

        return redirect()->route('proforma-fournisseur.show', $id)->with('success', 'Proforma modifiée avec succès!');
    }
}
