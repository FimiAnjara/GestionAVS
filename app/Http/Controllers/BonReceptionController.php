<?php

namespace App\Http\Controllers;

use App\Models\BonReception;
use App\Models\BonReceptionFille;
use App\Models\BonCommande;
use App\Models\MvtStock;
use App\Models\Article;
use App\Models\Emplacement;
use App\Models\Stock;
use App\Models\Fournisseur;
use App\Models\Magasin;
use Illuminate\Http\Request;
use PDF;

class BonReceptionController extends Controller
{
    /**
     * Afficher la liste des bons de réception
     */
    public function list(Request $request)
    {
        $query = BonReception::with(['bonCommande.proformaFournisseur.fournisseur', 'magasin.site.entite']);
        
        if ($request->filled('id_magasin')) {
            $query->where('id_magasin', $request->id_magasin);
        }

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
        $magasins = Magasin::with('site.entite')->get();
        
        return view('bon-reception.list', compact('bonReceptions', 'magasins'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $bonCommandes = BonCommande::where('etat', '>=', 11)->with('proformaFournisseur.fournisseur', 'bonCommandeFille.article')->get();
        $articles = Article::with('unite')->get();
        $fournisseurs = Fournisseur::where('deleted_at', null)->get();
        $magasins = Magasin::with('site.entite')->where('deleted_at', null)->get();
        $emplacements = Emplacement::all();

        $articlesJS = $articles->map(fn($a) => [
            'id' => $a->id_article, 
            'nom' => $a->nom,
            'unite' => $a->unite?->libelle,
            'photo' => $a->photo ? asset('storage/' . $a->photo) : ''
        ])->values();
        
        return view('bon-reception.create', compact('bonCommandes', 'articles', 'fournisseurs', 'magasins', 'emplacements', 'articlesJS'));
    }

    /**
     * Récupérer les données d'un bon de commande pour pré-remplissage (AJAX)
     */
    public function getBonCommandeData($id)
    {
        $bc = BonCommande::with(['proformaFournisseur.fournisseur', 'bonCommandeFille.article.unite', 'magasin.site.entite'])->find($id);
        
        if (!$bc) {
            return response()->json(['error' => 'Bon de Commande non trouvé'], 404);
        }
        
        return response()->json([
            'fournisseur_id' => $bc->proformaFournisseur?->id_fournisseur,
            'fournisseur_nom' => $bc->proformaFournisseur?->fournisseur?->nom,
            'id_magasin' => $bc->id_magasin,
            'description' => $bc->description,
            'articles' => $bc->bonCommandeFille->map(function($item) {
                return [
                    'id_article' => $item->id_article,
                    'nom' => $item->article->nom,
                    'unite' => $item->article->unite?->libelle,
                    'photo' => $item->article->photo ? asset('storage/' . $item->article->photo) : '',
                    'quantite' => $item->quantite,
                ];
            }),
        ]);
    }
    
    /**
     * Stocker un nouveau bon de réception
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'id_bonCommande' => 'required|exists:bonCommande,id_bonCommande',
            'id_magasin' => 'required|exists:magasin,id_magasin',
            'etat' => 'required|in:1,11,0',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.date_expiration' => 'nullable|date',
        ]);
        
        $id = 'BR_' . uniqid();
        
        // Récupérer le fournisseur du bon de commande
        $bonCommande = BonCommande::with('proformaFournisseur.fournisseur')->findOrFail($request->id_bonCommande);
        $idFournisseur = $bonCommande->proformaFournisseur?->fournisseur?->id_fournisseur;
        
        $bonReception = BonReception::create([
            'id_bonReception' => $id,
            'date_' => $request->date_,
            'etat' => $request->etat ?? 1,
            'id_bonCommande' => $request->id_bonCommande,
            'id_fournisseur' => $idFournisseur,
            'id_magasin' => $request->id_magasin,
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
     * Valider un bon de réception (état 1 -> 11)
     */
    public function valider($id)
    {
        $bonReception = BonReception::findOrFail($id);
        
        if ($bonReception->etat != 1) {
            return back()->withErrors(['etat' => 'Seuls les bons créés peuvent être validés']);
        }
        
        $bonReception->update(['etat' => 11]);
        
        return back()->with('success', 'Bon de réception validé');
    }
    
    /**
     * Annuler un bon de réception (état 1|11 -> 0)
     */
    public function annuler($id)
    {
        $bonReception = BonReception::findOrFail($id);
        
        if ($bonReception->etat == 0) {
            return back()->withErrors(['etat' => 'Ce bon est déjà annulé']);
        }
        
        $bonReception->update(['etat' => 0]);
        
        return back()->with('success', 'Bon de réception annulé');
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
