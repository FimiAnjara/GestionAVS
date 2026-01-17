<?php

namespace App\Http\Controllers;

use App\Models\BonReception;
use App\Models\BonReceptionFille;
use App\Models\BonCommande;
use App\Models\MvtStock;
use App\Models\Article;
use App\Models\Emplacement;
use App\Models\Stock;
use Illuminate\Http\Request;
use PDF;

class BonReceptionController extends Controller
{
    /**
     * Afficher la liste des bons de réception
     */
    public function list(Request $request)
    {
        $query = BonReception::query();
        
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
            $query->where('id_bonReception', 'like', '%' . $request->id . '%');
        }
        
        $bonReceptions = $query->latest('date_')->paginate(10);
        
        return view('bon-reception.list', compact('bonReceptions'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $bonCommandes = BonCommande::where('etat', '>=', 11)->with('proformaFournisseur.fournisseur')->get();
        $articles = Article::all();
        $emplacements = Emplacement::all();
        
        return view('bon-reception.create', compact('bonCommandes', 'articles', 'emplacements'));
    }
    
    /**
     * Stocker un nouveau bon de réception
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'id_bonCommande' => 'required|exists:bonCommande,id_bonCommande',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.date_expiration' => 'nullable|date',
        ]);
        
        $id = 'BR_' . uniqid();
        $bonReception = BonReception::create([
            'id_bonReception' => $id,
            'date_' => $request->date_,
            'etat' => 1,
            'id_bonCommande' => $request->id_bonCommande,
        ]);
        
        // Ajouter les articles
        foreach ($request->articles as $index => $article) {
            if (!empty($article['id_article'])) {
                BonReceptionFille::create([
                    'id_bonReceptionFille' => 'BRF_' . uniqid(),
                    'quantite' => $article['quantite'],
                    'date_expiration' => $article['date_expiration'] ?? null,
                    'id_bonReception' => $id,
                    'id_article' => $article['id_article'],
                ]);
            }
        }
        
        return redirect()->route('bon-reception.show', $id)->with('success', 'Bon de réception créé avec succès');
    }
    
    /**
     * Afficher les détails d'un bon de réception
     */
    public function show($id)
    {
        $bonReception = BonReception::findOrFail($id);
        $articles = $bonReception->bonReceptionFille()->with('article')->get();
        
        return view('bon-reception.show', compact('bonReception', 'articles'));
    }
    
    /**
     * Réceptionner les articles (créer mouvements de stock)
     */
    public function recevoir(Request $request, $id)
    {
        $bonReception = BonReception::findOrFail($id);
        
        if ($bonReception->etat != 1) {
            return back()->withErrors(['etat' => 'Ce bon a déjà été traité']);
        }
        
        $request->validate([
            'mouvements' => 'required|array|min:1',
            'mouvements.*.id_article' => 'required|exists:article,id_article',
            'mouvements.*.quantite' => 'required|numeric|min:1',
            'mouvements.*.id_emplacement' => 'required|exists:emplacement,id_emplacement',
        ]);
        
        // Créer les mouvements de stock
        foreach ($request->mouvements as $mouvement) {
            $stock = Stock::firstOrCreate(
                ['id_article' => $mouvement['id_article']],
                ['id_stock' => 'STOCK_' . uniqid(), 'quantite' => 0]
            );
            
            $quantite = $mouvement['quantite'];
            $stock->quantite += $quantite;
            $stock->save();
            
            // Récupérer la date d'expiration de l'article du bon
            $articleReception = $bonReception->bonReceptionFille()
                ->where('id_article', $mouvement['id_article'])
                ->first();
            
            MvtStock::create([
                'id_mvt_stock' => 'MVT_' . uniqid(),
                'entree' => $quantite,
                'sortie' => 0,
                'date_' => now(),
                'id_emplacement' => $mouvement['id_emplacement'],
                'id_article' => $mouvement['id_article'],
                'id_stock' => $stock->id_stock,
                'id_bonReception' => $id,
                'date_expiration' => $articleReception?->date_expiration,
            ]);
        }
        
        $bonReception->update(['etat' => 11]);
        
        return back()->with('success', 'Mouvements de stock créés et bon réceptionné');
    }
    
    /**
     * Exporter en PDF
     */
    public function exportPdf($id)
    {
        $bonReception = BonReception::findOrFail($id);
        $articles = $bonReception->bonReceptionFille()->with('article')->get();
        
        $pdf = PDF::loadView('bon-reception.pdf', compact('bonReception', 'articles'));
        return $pdf->download($bonReception->id_bonReception . '.pdf');
    }
    
    /**
     * Supprimer
     */
    public function destroy($id)
    {
        $bonReception = BonReception::findOrFail($id);
        $bonReception->delete();
        
        return redirect()->route('bon-reception.list')->with('success', 'Bon de réception supprimé');
    }
}
