<?php

namespace App\Http\Controllers\Ventes;

use App\Http\Controllers\Controller;
use App\Models\Ventes\BonLivraisonClient;
use App\Models\Ventes\BonLivraisonClientFille;
use App\Models\Ventes\BonCommandeClient;
use App\Models\Magasin;
use App\Models\Article;
use App\Services\MvtStockService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BonLivraisonClientController extends Controller
{
    protected $mvtStockService;

    public function __construct(MvtStockService $mvtStockService)
    {
        $this->mvtStockService = $mvtStockService;
    }

    public function index(Request $request)
    {
        $query = BonLivraisonClient::with(['client', 'magasin']);

        if ($request->id) {
            $query->where('id_bon_livraison_client', 'LIKE', '%' . $request->id . '%');
        }
        if ($request->date_from) {
            $query->whereDate('date_', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date_', '<=', $request->date_to);
        }
        if ($request->id_magasin) {
            $query->where('id_magasin', $request->id_magasin);
        }
        if ($request->etat !== null && $request->etat !== '') {
            $query->where('etat', $request->etat);
        }

        $bonLivraisons = $query->latest()->paginate(10);
        $magasins = Magasin::with('site.entite')->get();

        return view('bon-livraison-client.list', compact('bonLivraisons', 'magasins'));
    }

    public function create(Request $request)
    {
        $bonCommandes = BonCommandeClient::where('etat', 11)->with('client', 'bonCommandeClientFille.article')->get();
        $magasins = Magasin::with('site.entite')->get();
        $articles = Article::with('unite')->get();
        
        $bonCommandePreselected = null;
        if ($request->has('bon_commande_id')) {
            $bonCommandePreselected = BonCommandeClient::with(['client', 'bonCommandeClientFille.article.unite'])->find($request->bon_commande_id);
        }

        $articlesJS = $articles->map(fn($a) => [
            'id' => $a->id_article, 
            'nom' => $a->nom,
            'unite' => $a->unite?->libelle,
            'id_entite' => $a->id_entite,
            'photo' => $a->photo ? asset('storage/' . $a->photo) : ''
        ])->values();

        return view('bon-livraison-client.create', compact('bonCommandes', 'magasins', 'articles', 'articlesJS', 'bonCommandePreselected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'id_bon_commande_client' => 'required',
            'id_magasin' => 'required',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required',
            'articles.*.quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $bl = BonLivraisonClient::create([
                'date_' => $request->date_,
                'id_bon_commande_client' => $request->id_bon_commande_client,
                'id_client' => $request->id_client,
                'id_magasin' => $request->id_magasin,
                'description' => $request->description,
                'etat' => 1, // Créée
            ]);

            foreach ($request->articles as $art) {
                BonLivraisonClientFille::create([
                    'id_bon_livraison_client' => $bl->id_bon_livraison_client,
                    'id_article' => $art['id_article'],
                    'quantite' => $art['quantite'],
                ]);
            }

            // Automatiquement valider et générer le mouvement de stock si demandé ou par défaut
            // Pour ce projet, on va le faire via une étape séparée ou automatique.
            // Le user a dit : "L'enregistrement de ce bon générera une Sortie de Stock automatique."
            
            $this->validerEtSortirStock($bl->id_bon_livraison_client);

            DB::commit();
            return redirect()->route('bon-livraison-client.show', $bl->id_bon_livraison_client)->with('success', 'Bon de livraison enregistré et stock sorti.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $bonLivraison = BonLivraisonClient::with(['bonLivraisonClientFille.article.unite', 'client', 'magasin.site.entite'])->findOrFail($id);
        return view('bon-livraison-client.show', compact('bonLivraison'));
    }

    public function valider($id)
    {
        try {
            DB::beginTransaction();
            $this->validerEtSortirStock($id);
            DB::commit();
            return back()->with('success', 'Bon de livraison validé et stock mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    protected function validerEtSortirStock($id)
    {
        $bl = BonLivraisonClient::with('bonLivraisonClientFille.article')->findOrFail($id);
        
        if ($bl->etat == 2) {
            return; // Déjà livré
        }

        // Préparer les données pour MvtStockService
        $articlesMvt = [];
        foreach ($bl->bonLivraisonClientFille as $item) {
            $articlesMvt[] = [
                'id_article' => $item->id_article,
                'sortie' => $item->quantite,
            ];
        }

        // Créer le mouvement de stock
        $this->mvtStockService->createMovement([
            'id_mvt_stock' => 'MVT_SORTIE_' . uniqid(),
            'date_' => $bl->date_,
            'id_magasin' => $bl->id_magasin,
            'id_type_mvt' => 'S', // Sortie
            'description' => 'Livraison client ' . $bl->id_bon_livraison_client,
            'montant_total' => 0, // Sera calculé par le service ou pas nécessaire ici
            'articles' => $articlesMvt
        ]);

        $bl->update(['etat' => 2]); // Livré
    }

    public function getBonCommandeData($id)
    {
        $bc = BonCommandeClient::with(['client', 'bonCommandeClientFille.article.unite'])->find($id);
        if (!$bc) return response()->json(['error' => 'Non trouvé'], 404);

        return response()->json([
            'client_id' => $bc->id_client,
            'client_nom' => $bc->client->nom,
            'id_magasin' => $bc->id_magasin,
            'description' => $bc->description,
            'articles' => $bc->bonCommandeClientFille->map(function($item) {
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

    public function exportPdf($id)
    {
        $bonLivraison = BonLivraisonClient::with(['bonLivraisonClientFille.article', 'client'])->findOrFail($id);
        $pdf = Pdf::loadView('bon-livraison-client.pdf', compact('bonLivraison'));
        return $pdf->stream('BL_client_' . $bonLivraison->id_bon_livraison_client . '.pdf');
    }

    public function destroy($id)
    {
        $bl = BonLivraisonClient::findOrFail($id);
        if ($bl->etat == 2) {
            return back()->with('error', 'Impossible de supprimer un bon déjà livré.');
        }
        $bl->delete();
        return redirect()->route('bon-livraison-client.list')->with('success', 'Bon de livraison supprimé.');
    }
}
