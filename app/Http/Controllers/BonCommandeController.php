<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\BonCommandeFille;
use App\Models\ProformaFournisseur;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use PDF;

class BonCommandeController extends Controller
{
    /**
     * Afficher la liste des bons de commande
     */
    public function list(Request $request)
    {
        $query = BonCommande::with(['proformaFournisseur.fournisseur', 'magasin.site.entite']);
        
        // Filtrer par magasin
        if ($request->filled('id_magasin')) {
            $query->where('id_magasin', $request->id_magasin);
        }

        // Filtrer par fournisseur
        if ($request->filled('fournisseur')) {
            $query->whereHas('proformaFournisseur', function ($q) {
                $q->where('id_fournisseur', request('fournisseur'));
            });
        }
        
        // Filtrer par date
        if ($request->filled('date_from')) {
            $query->where('date_', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_', '<=', $request->date_to);
        }
        
        // Filtrer par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }
        
        // Filtrer par ID
        if ($request->filled('id')) {
            $query->where('id_bonCommande', 'like', '%' . $request->id . '%');
        }
        
        $bonCommandes = $query->latest('date_')->paginate(10);
        $fournisseurs = Fournisseur::all();
        $magasins = \App\Models\Magasin::with('site.entite')->get();
        
        return view('bon-commande.list', compact('bonCommandes', 'fournisseurs', 'magasins'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $fournisseurs = Fournisseur::all();
        
        // Si proforma_id est passé, pré-remplir
        $proformaFournisseur = null;
        $articlesProforma = [];
        $descriptionProforma = '';
        $idMagasinProforma = null;
        
        if ($request->has('proforma_id')) {
            $proformaFournisseur = ProformaFournisseur::find($request->proforma_id);
            if (!$proformaFournisseur || $proformaFournisseur->etat < 5) {
                return redirect()->route('proforma-fournisseur.list')->with('error', 'Proforma invalide ou non validée par Finance');
            }
            
            // Récupérer les articles de la proforma
            $articlesProforma = $proformaFournisseur->proformaFournisseurFille()->with('article')->get();
            $descriptionProforma = $proformaFournisseur->description ?? '';
            $idMagasinProforma = $proformaFournisseur->id_magasin;
        }
        
        $proformas = ProformaFournisseur::where('etat', '>=', 5)->get();
        $magasins = \App\Models\Magasin::with('site.entite')->get();
        
        $articles = Article::with('unite')->get();
        $articlesJS = $articles->map(fn($a) => [
            'id' => $a->id_article, 
            'nom' => $a->nom,
            'unite' => $a->unite?->libelle,
            'photo' => $a->photo ? asset('storage/' . $a->photo) : ''
        ])->values();
        
        return view('bon-commande.create', compact('fournisseurs', 'proformas', 'articles', 'proformaFournisseur', 'articlesProforma', 'descriptionProforma', 'articlesJS', 'magasins', 'idMagasinProforma'));
    }
    
    /**
     * Stocker un nouveau bon de commande
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'description' => 'nullable|string',
            'id_fournisseur' => 'required|exists:fournisseur,id_fournisseur',
            'id_proformaFournisseur' => 'required|exists:proformaFournisseur,id_proformaFournisseur',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required|exists:article,id_article',
            'articles.*.quantite' => 'required|numeric|min:1',
            'articles.*.prix' => 'required|numeric|min:0',
        ]);
        
        // Vérifier que la proforma a l'état >= 5
        $proforma = ProformaFournisseur::find($request->id_proformaFournisseur);
        if ($proforma->etat < 5) {
            return back()->withErrors(['id_proformaFournisseur' => 'La proforma doit être validée par Finance']);
        }
        
        // Créer le bon de commande
        $id = 'BC_' . uniqid();
        $userId = auth()->check() ? auth()->user()->id : (Utilisateur::first()?->id_utilisateur ?? 'UTIL-1');
        
        $bonCommande = BonCommande::create([
            'id_bonCommande' => $id,
            'date_' => $request->date_,
            'etat' => 1, // Créée
            'id_utilisateur' => $userId,
            'id_proformaFournisseur' => $request->id_proformaFournisseur,
            'id_magasin' => $request->id_magasin,
            'description' => $request->description,
        ]);
        
        // Ajouter les articles
        foreach ($request->articles as $article) {
            if (!empty($article['id_article'])) {
                BonCommandeFille::create([
                    'id_bonCommandeFille' => 'BCF_' . uniqid(),
                    'quantite' => $article['quantite'],
                    'prix_achat' => $article['prix'],
                    'id_bonCommande' => $id,
                    'id_article' => $article['id_article'],
                ]);
            }
        }
        
        return redirect()->route('bon-commande.show', $id)->with('success', 'Bon de commande créé avec succès');
    }
    
    /**
     * Afficher les détails d'un bon de commande
     */
    public function show($id)
    {
        $bonCommande = BonCommande::findOrFail($id);
        $articles = $bonCommande->bonCommandeFille()->with('article.unite')->get();
        
        return view('bon-commande.show', compact('bonCommande', 'articles'));
    }
    
    /**
     * Changer l'état du bon de commande
     */
    public function changerEtat(Request $request, $id)
    {
        $bonCommande = BonCommande::findOrFail($id);
        
        $request->validate([
            'etat' => 'required|in:1,5,11,0',
        ]);
        
        $bonCommande->update(['etat' => $request->etat]);
        
        return back()->with('success', 'État mis à jour avec succès');
    }
    
    /**
     * Récupérer les données d'une proforma pour pré-remplissage (AJAX)
     */
    public function getProformaData($id)
    {
        $proforma = ProformaFournisseur::with('proformaFournisseurFille.article', 'fournisseur')->find($id);
        
        if (!$proforma) {
            return response()->json(['error' => 'Proforma non trouvée'], 404);
        }
        
        return response()->json([
            'fournisseur_id' => $proforma->id_fournisseur,
            'fournisseur_nom' => $proforma->fournisseur->nom,
            'description' => $proforma->description,
            'id_magasin' => $proforma->id_magasin,
            'articles' => $proforma->proformaFournisseurFille->map(function($item) {
                return [
                    'id_article' => $item->id_article,
                    'nom' => $item->article->nom,
                    'unite' => $item->article->unite?->libelle,
                    'photo' => $item->article->photo ? asset('storage/' . $item->article->photo) : '',
                    'quantite' => $item->quantite,
                    'prix' => $item->prix_achat,
                ];
            }),
        ]);
    }
    
    /**
     * Exporter en PDF
     */
    public function exportPdf($id)
    {
        $bonCommande = BonCommande::findOrFail($id);
        $articles = $bonCommande->bonCommandeFille()->with('article')->get();
        
        $pdf = PDF::loadView('bon-commande.pdf', compact('bonCommande', 'articles'));
        return $pdf->download($bonCommande->id_bonCommande . '.pdf');
    }
    
    /**
     * Supprimer un bon de commande
     */
    public function destroy($id)
    {
        $bonCommande = BonCommande::findOrFail($id);
        $bonCommande->delete();
        
        return redirect()->route('bon-commande.list')->with('success', 'Bon de commande supprimé avec succès');
    }
}
