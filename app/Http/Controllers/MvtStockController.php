<?php

namespace App\Http\Controllers;

use App\Models\MvtStock;
use App\Models\MvtStockFille;
use App\Models\Article;
use App\Models\Magasin;
use App\Models\BonReception;
use App\Services\MagasinService;
use App\Services\MvtStockService;
use Illuminate\Http\Request;

class MvtStockController extends Controller
{
    protected $magasinService;
    protected $mvtStockService;

    public function __construct(MagasinService $magasinService, MvtStockService $mvtStockService)
    {
        $this->magasinService = $magasinService;
        $this->mvtStockService = $mvtStockService;
    }

    /**
     * Dashboard du stock avec valorisation par entité et magasin
     */
    public function dashboard()
    {
        try {
            // Valeur du stock par entité
            $stockParEntite = $this->magasinService->getValeurStockParEntite();
            
            // Valeur du stock par magasin
            $valeursStock = $this->magasinService->getValeurStockTousMagasins();
            $stockParMagasin = array_map(function($item) {
                return [
                    'magasin' => $item['magasin'],
                    'valeur' => $item['valeur'],
                    'nb_articles' => $item['nb_articles'],
                ];
            }, $valeursStock);

            // Valeur totale
            $valeurTotaleStock = array_sum(array_column($stockParMagasin, 'valeur'));
            
            // Total articles en stock
            $totalArticlesEnStock = array_sum(array_column($stockParMagasin, 'nb_articles'));

            // Top 10 articles par valeur de stock
            $topArticles = $this->getTopArticlesParValeur(10);

            return view('mvt-stock.dashboard', compact(
                'stockParEntite',
                'stockParMagasin',
                'valeurTotaleStock',
                'totalArticlesEnStock',
                'topArticles'
            ));

        } catch (\Exception $e) {
            \Log::error('Erreur dashboard stock: ' . $e->getMessage());
            
            return view('mvt-stock.dashboard', [
                'stockParEntite' => [],
                'stockParMagasin' => [],
                'valeurTotaleStock' => 0,
                'totalArticlesEnStock' => 0,
                'topArticles' => [],
                'error' => 'Une erreur est survenue lors du chargement du dashboard.'
            ]);
        }
    }

    /**
     * Récupérer les top articles par valeur de stock
     */
    protected function getTopArticlesParValeur(int $limit = 10): array
    {
        $articles = Article::with(['typeEvaluation', 'unite'])->whereNull('deleted_at')->get();
        $articlesValeur = [];

        foreach ($articles as $article) {
            // Récupérer tous les lots disponibles pour cet article (tous magasins)
            $batches = MvtStockFille::whereHas('mvtStock', function($q) {
                    $q->whereNull('deleted_at');
                })
                ->where('id_article', $article->id_article)
                ->where('entree', '>', 0)
                ->where('reste', '>', 0)
                ->whereNull('deleted_at')
                ->get();

            $quantiteTotale = $batches->sum('reste');
            
            if ($quantiteTotale <= 0) continue;

            // Calculer la valeur (somme de reste * prix_unitaire)
            $valeurTotale = $batches->sum(function($batch) {
                return floatval($batch->reste) * floatval($batch->prix_unitaire);
            });

            $methode = $article->typeEvaluation->id_type_evaluation_stock ?? 'CMUP';
            $prixUnitaire = $quantiteTotale > 0 ? $valeurTotale / $quantiteTotale : 0;

            $articlesValeur[] = [
                'article' => $article,
                'methode' => $methode,
                'quantite' => $quantiteTotale,
                'prix_unitaire' => $prixUnitaire,
                'valeur' => $valeurTotale,
            ];
        }

        // Trier par valeur décroissante et limiter
        usort($articlesValeur, function($a, $b) {
            return $b['valeur'] <=> $a['valeur'];
        });

        return array_slice($articlesValeur, 0, $limit);
    }

    /**
     * Afficher la liste des mouvements de stock
     */
    public function list(Request $request)
    {
        $query = MvtStock::with('mvtStockFille.article.unite', 'magasin');
        
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
        $mouvement = MvtStock::with('mvtStockFille.article.unite')->findOrFail($id);
        
        return view('mvt-stock.show', compact('mouvement'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $articles = Article::with(['unite', 'categorie'])->get();
        $magasins = Magasin::with('site.entite')->get();
        $typeMvts = \App\Models\TypeMvtStock::all();
        
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
                    'photo' => $item->article?->photo,
                    'unite' => $item->article?->unite?->libelle,
                    'est_perissable' => $item->article?->categorie?->est_perissable ?? false,
                    'date_expiration' => $item->date_expiration ? $item->date_expiration->format('Y-m-d') : null,
                ];
            })->toArray();
        }
        
        $prefilledTypeMvt = $request->id_type_mvt;
        
        return view('mvt-stock.create', compact('articles', 'magasins', 'bonReception', 'prefilledArticles', 'prefilledMagasin', 'typeMvts', 'prefilledTypeMvt'));
    }

    /**
     * Enregistrer un nouveau mouvement avec ses articles
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mvt_stock' => 'required|unique:mvt_stock',
            'date_' => 'required|date',
            'id_magasin' => 'required|exists:magasin,id_magasin',
            'id_type_mvt' => 'required|exists:type_mvt_stock,id_type_mvt',
            'description' => 'nullable|string',
            'montant_total' => 'required|numeric|min:0',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.entree' => 'nullable|numeric|min:0',
            'articles.*.sortie' => 'nullable|numeric|min:0',
            'articles.*.prix_unitaire' => 'nullable|numeric|min:0',
            'articles.*.date_expiration' => 'nullable|date',
        ]);

        try {
            $this->mvtStockService->createMovement($validated);
            return redirect()->route('mvt-stock.list')->with('success', 'Mouvement de stock créé avec succès');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du mouvement : ' . $e->getMessage()]);
        }
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
        // Récupérer toutes les entités pour le filtre
        $entites = \App\Models\Entite::where('deleted_at', null)->get();
        
        // Construire la requête des magasins avec filtres
        $magasinsQuery = Magasin::where('deleted_at', null)
            ->with(['site.entite']);
        
        // Filtre par entité
        if ($request->filled('entite_id')) {
            $magasinsQuery->whereHas('site', function($q) use ($request) {
                $q->where('id_entite', $request->entite_id);
            });
        }
        
        // Filtre par magasin
        if ($request->filled('magasin_id')) {
            $magasinsQuery->where('id_magasin', $request->magasin_id);
        }
        
        $magasins = $magasinsQuery->get();
        $allMagasins = Magasin::where('deleted_at', null)->with(['site.entite'])->get();
        
        // Récupérer les 10 derniers mouvements avec les filtres
        $mouvementsQuery = MvtStockFille::with(['mvtStock.magasin.site.entite', 'article.unite'])
            ->orderBy('created_at', 'desc');
        
        // Appliquer les filtres
        if ($request->filled('entite_id')) {
            $mouvementsQuery->whereHas('mvtStock.magasin.site', function($q) use ($request) {
                $q->where('id_entite', $request->entite_id);
            });
        }
        
        if ($request->filled('magasin_id')) {
            $mouvementsQuery->whereHas('mvtStock', function($q) use ($request) {
                $q->where('id_magasin', $request->magasin_id);
            });
        }
        
        $derniersMovements = $mouvementsQuery->take(10)->get();
        
        return view('mvt-stock.etat', compact('derniersMovements', 'allMagasins', 'entites'));
    }

    /**
     * Afficher les détails de tous les mouvements (articles enfants)
     */
    public function details(Request $request)
    {
        $query = MvtStockFille::with('mvtStock.magasin', 'article.unite');
        
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

    /**
     * Supprimer un mouvement de stock (Soft Delete)
     */
    public function destroy($id)
    {
        $mvt = MvtStock::findOrFail($id);
        $mvt->delete(); // Soft delete parent
        
        // Supprimer aussi les filles (soft delete cascade si configuré ou manuel)
        $mvt->mvtStockFille()->delete();
        
        return redirect()->route('mvt-stock.list')->with('success', 'Mouvement supprimé avec succès');
    }

    /**
     * Supprimer une ligne de mouvement spécifique (Soft Delete)
     */
    public function destroyFille($id)
    {
        $fille = MvtStockFille::findOrFail($id);
        $idMvtStock = $fille->id_mvt_stock;
        $fille->delete();
        
        // Recalculer le montant total du mouvement parent
        $mvt = MvtStock::find($idMvtStock);
        if ($mvt) {
            $mvt->montant_total = $mvt->mvtStockFille->sum(function($f) {
                return (($f->entree ?? 0) + ($f->sortie ?? 0)) * ($f->prix_unitaire ?? 0);
            });
            $mvt->save();
        }
        
        return back()->with('success', 'Ligne de mouvement supprimée avec succès');
    }

    /**
     * Formulaire d'édition d'une ligne de mouvement spécifique
     */
    public function editFille($id)
    {
        $fille = MvtStockFille::with('mvtStock', 'article')->findOrFail($id);
        return view('mvt-stock.edit-fille', compact('fille'));
    }

    /**
     * Mettre à jour une ligne de mouvement spécifique
     */
    public function updateFille(Request $request, $id)
    {
        $fille = MvtStockFille::findOrFail($id);
        
        $validated = $request->validate([
            'entree' => 'nullable|numeric|min:0',
            'sortie' => 'nullable|numeric|min:0',
            'prix_unitaire' => 'nullable|numeric|min:0',
            'reste' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
        ]);

        // Si on modifie l'entree d'un lot d'entrée, on ajuste le reste proportionnellement
        // pour ne pas fausser le stock disponible si des sorties ont déjà eu lieu
        if (isset($validated['entree']) && $fille->entree > 0) {
            $difference = $validated['entree'] - $fille->entree;
            $newReste = $fille->reste + $difference;
            
            if ($newReste < 0) {
                return back()->withErrors(['error' => 'La nouvelle quantité d\'entrée est inférieure aux sorties déjà effectuées sur ce lot.']);
            }
            $validated['reste'] = $newReste;
        }

        $fille->update($validated);

        // Recalculer le montant total du mouvement parent
        $mvt = MvtStock::find($fille->id_mvt_stock);
        if ($mvt) {
            $mvt->montant_total = $mvt->mvtStockFille->sum(function($f) {
                return (($f->entree ?? 0) + ($f->sortie ?? 0)) * ($f->prix_unitaire ?? 0);
            });
            $mvt->save();
        }

        return redirect()->route('stock.details')->with('success', 'Ligne de mouvement mise à jour avec succès');
    }

    /**
     * API: Obtenir le prix unitaire actuel d'un article dans un magasin
     */
    public function getPrixActuel(Request $request)
    {
        $idArticle = $request->input('id_article');
        $idMagasin = $request->input('id_magasin');

        if (!$idArticle || !$idMagasin) {
            return response()->json(['prix' => 0]);
        }

        $article = Article::find($idArticle);
        if (!$article) {
            return response()->json(['prix' => 0]);
        }

        $prix = $article->getPrixActuel($idMagasin);

        return response()->json(['prix' => $prix]);
    }
}