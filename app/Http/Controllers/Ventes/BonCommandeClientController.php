<?php

namespace App\Http\Controllers\Ventes;

use App\Http\Controllers\Controller;
use App\Models\Ventes\BonCommandeClient;
use App\Models\Ventes\BonCommandeClientFille;
use App\Models\Ventes\ProformaClient;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Article;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BonCommandeClientController extends Controller
{
    public function index(Request $request)
    {
        $query = BonCommandeClient::with(['client', 'magasin']);

        if ($request->id) {
            $query->where('id_bon_commande_client', 'LIKE', '%' . $request->id . '%');
        }
        if ($request->client) {
            $query->where('id_client', $request->client);
        }
        if ($request->date_from) {
            $query->whereDate('date_', '>=', $request->date_from);
        }
        if ($request->etat !== null && $request->etat !== '') {
            $query->where('etat', $request->etat);
        }

        $bonCommandes = $query->latest()->paginate(10);
        $clients = Client::all();
        $magasins = Magasin::all();

        return view('bon-commande-client.list', compact('bonCommandes', 'clients', 'magasins'));
    }

    public function create(Request $request)
    {
        $proformaClient = null;
        $articlesProforma = [];
        $descriptionProforma = '';
        $idMagasinProforma = null;

        if ($request->has('proforma_id')) {
            $proformaClient = ProformaClient::with('proformaClientFille.article')->find($request->proforma_id);
            if ($proformaClient) {
                $articlesProforma = $proformaClient->proformaClientFille;
                $descriptionProforma = $proformaClient->description;
                $idMagasinProforma = $proformaClient->id_magasin;
            }
        }

        $proformas = ProformaClient::where('etat', 11)->get(); // Validée
        $magasins = Magasin::with('site.entite')->get();
        $articles = Article::with('unite')->get();
        $articlesJS = $articles->map(fn($a) => [
            'id' => $a->id_article, 
            'nom' => $a->nom,
            'unite' => $a->unite?->libelle,
            'photo' => $a->photo ? asset('storage/' . $a->photo) : ''
        ])->values();

        return view('bon-commande-client.create', compact('proformas', 'magasins', 'articles', 'proformaClient', 'articlesProforma', 'descriptionProforma', 'idMagasinProforma', 'articlesJS'));
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

            $bc = BonCommandeClient::create([
                'date_' => $request->date_,
                'id_client' => $request->id_client,
                'id_proforma_client' => $request->id_proforma_client,
                'id_magasin' => $request->id_magasin,
                'description' => $request->description,
                'etat' => 1, // Créée
            ]);

            foreach ($request->articles as $art) {
                BonCommandeClientFille::create([
                    'id_bon_commande_client' => $bc->id_bon_commande_client,
                    'id_article' => $art['id_article'],
                    'quantite' => $art['quantite'],
                    'prix' => $art['prix'],
                ]);
            }

            DB::commit();
            return redirect()->route('bon-commande-client.list')->with('success', 'Bon de commande client enregistré.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $bonCommande = BonCommandeClient::with(['bonCommandeClientFille.article.unite', 'client', 'magasin.site.entite'])->findOrFail($id);
        return view('bon-commande-client.show', compact('bonCommande'));
    }

    public function changeEtat($id, Request $request)
    {
        $bc = BonCommandeClient::findOrFail($id);
        $bc->update(['etat' => $request->etat]);
        return back()->with('success', 'État mis à jour.');
    }

    public function getProformaData($id)
    {
        $proforma = ProformaClient::with('proformaClientFille.article.unite', 'client')->find($id);
        if (!$proforma) return response()->json(['error' => 'Non trouvée'], 404);

        return response()->json([
            'client_id' => $proforma->id_client,
            'client_nom' => $proforma->client->nom,
            'description' => $proforma->description,
            'id_magasin' => $proforma->id_magasin,
            'articles' => $proforma->proformaClientFille->map(function($item) {
                return [
                    'id_article' => $item->id_article,
                    'nom' => $item->article->nom,
                    'unite' => $item->article->unite?->libelle,
                    'photo' => $item->article->photo ? asset('storage/' . $item->article->photo) : '',
                    'quantite' => $item->quantite,
                    'prix' => $item->prix,
                ];
            }),
        ]);
    }

    public function exportPdf($id)
    {
        $bonCommande = BonCommandeClient::with(['bonCommandeClientFille.article', 'client'])->findOrFail($id);
        $pdf = Pdf::loadView('bon-commande-client.pdf', compact('bonCommande'));
        return $pdf->stream('BC_client_' . $bonCommande->id_bon_commande_client . '.pdf');
    }

    public function destroy($id)
    {
        $bc = BonCommandeClient::findOrFail($id);
        $bc->delete();
        return redirect()->route('bon-commande-client.list')->with('success', 'Bon de commande supprimé.');
    }
}
