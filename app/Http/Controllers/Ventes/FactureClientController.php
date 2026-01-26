<?php

namespace App\Http\Controllers\Ventes;

use App\Http\Controllers\Controller;
use App\Models\Ventes\FactureClient;
use App\Models\Ventes\FactureClientFille;
use App\Models\Ventes\BonCommandeClient;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Article;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class FactureClientController extends Controller
{
    public function index(Request $request)
    {
        $query = FactureClient::with(['client', 'bonCommandeClient', 'factureClientFille']);

        if ($request->id) {
            $query->where('id_facture_client', 'LIKE', '%' . $request->id . '%');
        }
        if ($request->client) {
            $query->where('id_client', $request->client);
        }
        if ($request->date_from) {
            $query->whereDate('date_', '>=', $request->date_from);
        }

        $factures = $query->latest()->paginate(10);
        $clients = Client::all();
        $magasins = Magasin::all();

        return view('facture-client.list', compact('factures', 'clients', 'magasins'));
    }

    public function create(Request $request)
    {
        $bonCommande = null;
        if ($request->has('bon_commande_id')) {
            $bonCommande = BonCommandeClient::with(['client', 'bonCommandeClientFille.article.unite'])->findOrFail($request->bon_commande_id);
            
            if ($bonCommande->etat != 11) {
                return back()->with('error', 'Le bon de commande doit être validé.');
            }
        }

        $articles = Article::with(['unite', 'articleFille'])->get();
        $articlesJS = $articles->map(fn($a) => [
            'id' => $a->id_article, 
            'nom' => $a->nom,
            'unite' => $a->unite?->libelle,
            'id_entite' => $a->id_entite,
            'photo' => $a->photo ? asset('storage/' . $a->photo) : '',
            'prix_vente' => $a->articleFille->first()?->prix ?? 0,
        ])->values();

        return view('facture-client.create', compact('bonCommande', 'articles', 'articlesJS'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_' => 'required|date',
            'id_client' => 'required',
            'articles' => 'required|array|min:1',
            'articles.*.id_article' => 'required',
            'articles.*.quantite' => 'required|numeric|min:0.01',
            'articles.*.prix' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $facture = FactureClient::create([
                'date_' => $request->date_,
                'id_client' => $request->id_client,
                'id_bon_commande_client' => $request->id_bon_commande_client,
                'description' => $request->description,
                'etat' => 1, // Créée
            ]);

            foreach ($request->articles as $art) {
                FactureClientFille::create([
                    'id_facture_client' => $facture->id_facture_client,
                    'id_article' => $art['id_article'],
                    'quantite' => $art['quantite'],
                    'prix' => $art['prix'],
                ]);
            }

            DB::commit();
            return redirect()->route('facture-client.list')->with('success', 'Facture client enregistrée.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $facture = FactureClient::with(['factureClientFille.article.unite', 'client', 'bonCommandeClient'])->findOrFail($id);
        return view('facture-client.show', compact('facture'));
    }

    public function changeEtat($id, Request $request)
    {
        $facture = FactureClient::findOrFail($id);
        $facture->update(['etat' => $request->etat]);
        return back()->with('success', 'État mis à jour.');
    }

    public function exportPdf($id)
    {
        $facture = FactureClient::with(['factureClientFille.article.unite', 'client'])->findOrFail($id);
        $pdf = Pdf::loadView('facture-client.pdf', compact('facture'));
        return $pdf->stream('facture_client_' . $facture->id_facture_client . '.pdf');
    }

    public function destroy($id)
    {
        $facture = FactureClient::findOrFail($id);
        $facture->delete();
        return redirect()->route('facture-client.list')->with('success', 'Facture supprimée.');
    }
}
