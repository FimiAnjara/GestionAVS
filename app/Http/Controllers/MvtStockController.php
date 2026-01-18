<?php

namespace App\Http\Controllers;

use App\Models\MvtStock;
use App\Models\MvtStockFille;
use App\Models\Article;
use App\Models\Magasin;
use App\Models\BonReception;
use Illuminate\Http\Request;

class MvtStockController extends Controller
{
    /**
     * Afficher la liste des mouvements de stock
     */
    public function list(Request $request)
    {
        $query = MvtStock::with('mvtStockFille.article', 'magasin');
        
        if ($request->filled('date_from')) {
            $query->where('date_', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_', '<=', $request->date_to . ' 23:59:59');
        }
        
        if ($request->filled('id_mvt_stock')) {
            $query->where('id_mvt_stock', 'like', '%' . $request->id_mvt_stock . '%');
        }
        
        if ($request->filled('id_magasin')) {
            $query->where('id_magasin', $request->id_magasin);
        }
        
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        
        $mouvements = $query->latest('date_')->paginate(15);
        
        $magasins = Magasin::where('deleted_at', null)->get();
        
        return view('mvt-stock.list', compact('mouvements', 'magasins'));
    }
    
    /**
     * Afficher les détails d'un mouvement
     */
    public function show($id)
    {
        $mouvement = MvtStock::with('mvtStockFille.article')->findOrFail($id);
        
        return view('mvt-stock.show', compact('mouvement'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $articles = Article::all();
        $magasins = Magasin::all();
        
        // Pré-remplissage depuis un bon de réception
        $bonReception = null;
        $prefilledArticles = [];
        $prefilledMagasin = null;
        
        if ($request->filled('from_bon_reception')) {
            $bonReception = BonReception::with('bonCommande.bonCommandeFille')->findOrFail($request->from_bon_reception);
            $prefilledMagasin = $bonReception->id_magasin;
            
            // Récupérer les articles du bon de réception avec les prix du bon de commande
            $prefilledArticles = $bonReception->bonReceptionFille->map(function($item) use ($bonReception) {
                // Chercher le prix unitaire dans le bon de commande
                $prixUnitaire = 0;
                if ($bonReception->bonCommande && $bonReception->bonCommande->bonCommandeFille) {
                    $bonCommandeFille = $bonReception->bonCommande->bonCommandeFille
                        ->where('id_article', $item->id_article)
                        ->first();
                    if ($bonCommandeFille) {
                        $prixUnitaire = $bonCommandeFille->prix_achat ?? 0;
                    }
                }
                
                return [
                    'id_article' => $item->id_article,
                    'quantite' => $item->quantite,
                    'prix_unitaire' => $prixUnitaire,
                    'nom' => $item->article?->nom ?? $item->id_article,
                ];
            })->toArray();
        }
        
        return view('mvt-stock.create', compact('articles', 'magasins', 'bonReception', 'prefilledArticles', 'prefilledMagasin'));
    }

    /**
     * Enregistrer un nouveau mouvement avec ses articles
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mvt_stock' => 'required|unique:mvt_stock',
            'date_' => 'required|date',
            'id_magasin' => 'nullable|exists:magasin,id_magasin',
            'description' => 'nullable|string',
            'montant_total' => 'required|numeric|min:0',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.entree' => 'nullable|numeric|min:0',
            'articles.*.sortie' => 'nullable|numeric|min:0',
            'articles.*.prix_unitaire' => 'nullable|numeric|min:0',
            'articles.*.date_expiration' => 'nullable|date',
        ]);

        // Créer le mouvement parent
        $mvtStock = MvtStock::create([
            'id_mvt_stock' => $validated['id_mvt_stock'],
            'date_' => $validated['date_'],
            'id_magasin' => $validated['id_magasin'],
            'description' => $validated['description'],
            'montant_total' => $validated['montant_total'],
        ]);

        // Créer les articles enfants
        foreach ($validated['articles'] as $index => $article) {
            MvtStockFille::create([
                'id_mvt_stock_fille' => $validated['id_mvt_stock'] . '_' . ($index + 1),
                'id_mvt_stock' => $validated['id_mvt_stock'],
                'id_article' => $article['id_article'],
                'entree' => $article['entree'] ?? 0,
                'sortie' => $article['sortie'] ?? 0,
                'prix_unitaire' => $article['prix_unitaire'] ?? 0,
                'date_expiration' => $article['date_expiration'] ?? null,
            ]);
        }

        return redirect()->route('mvt-stock.list')->with('success', 'Mouvement de stock créé avec succès');
    }

    /**
     * Exporter en PDF
     */
    public function exportPdf($id)
    {
        $mouvement = MvtStock::with('mvtStockFille.article')->findOrFail($id);
        
        $pdf = \PDF::loadView('mvt-stock.pdf', compact('mouvement'));
        return $pdf->download($mouvement->id_mvt_stock . '.pdf');
    }

    /**
     * Afficher l'état du stock par magasin
     */
    public function etat(Request $request)
    {
        $magasins = Magasin::where('deleted_at', null)->get();
        
        $etatStock = [];
        
        foreach ($magasins as $magasin) {
            // Récupérer tous les mouvements de ce magasin
            $mouvements = MvtStock::where('id_magasin', $magasin->id_magasin)
                ->with('mvtStockFille.article')
                ->get();
            
            $articles = [];
            
            foreach ($mouvements as $mvt) {
                foreach ($mvt->mvtStockFille as $fille) {
                    $idArticle = $fille->id_article;
                    
                    if (!isset($articles[$idArticle])) {
                        $articles[$idArticle] = [
                            'id_article' => $idArticle,
                            'designation' => $fille->article?->designation ?? 'Inconnu',
                            'entree' => 0,
                            'sortie' => 0,
                        ];
                    }
                    
                    $articles[$idArticle]['entree'] += $fille->entree;
                    $articles[$idArticle]['sortie'] += $fille->sortie;
                }
            }
            
            // Calculer les quantités restantes
            foreach ($articles as &$article) {
                $article['quantite_restante'] = $article['entree'] - $article['sortie'];
            }
            
            // Trier par designation
            usort($articles, function($a, $b) {
                return strcmp($a['designation'], $b['designation']);
            });
            
            $etatStock[$magasin->id_magasin] = [
                'magasin' => $magasin,
                'articles' => $articles,
            ];
        }
        
        return view('mvt-stock.etat', compact('etatStock', 'magasins'));
    }

    /**
     * Afficher les détails de tous les mouvements (articles enfants)
     */
    public function details(Request $request)
    {
        $query = MvtStockFille::with('mvtStock.magasin', 'article');
        
        // Filtres
        if ($request->filled('id_article')) {
            $query->where('id_article', 'like', '%' . $request->id_article . '%');
        }
        
        if ($request->filled('id_mvt_stock')) {
            $query->where('id_mvt_stock', 'like', '%' . $request->id_mvt_stock . '%');
        }
        
        if ($request->filled('date_from')) {
            $query->whereHas('mvtStock', function($q) {
                $q->where('date_', '>=', request('date_from'));
            });
        }
        
        if ($request->filled('date_to')) {
            $query->whereHas('mvtStock', function($q) {
                $q->where('date_', '<=', request('date_to') . ' 23:59:59');
            });
        }
        
        if ($request->filled('id_magasin')) {
            $query->whereHas('mvtStock', function($q) {
                $q->where('id_magasin', request('id_magasin'));
            });
        }
        
        $mouvementsFille = $query->orderBy('id_mvt_stock', 'desc')->paginate(20);
        
        $magasins = Magasin::where('deleted_at', null)->get();
        
        return view('mvt-stock.details', compact('mouvementsFille', 'magasins'));
    }
}