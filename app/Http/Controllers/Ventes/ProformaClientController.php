<?php

namespace App\Http\Controllers\Ventes;

use App\Http\Controllers\Controller;
use App\Models\Ventes\ProformaClient;
use App\Models\Ventes\ProformaClientFille;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Article;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProformaClientController extends Controller
{
    public function index(Request $request)
    {
        $query = ProformaClient::with(['client', 'magasin']);

        if ($request->id) {
            $query->where('id_proforma_client', 'LIKE', '%' . $request->id . '%');
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

        $proformas = $query->latest()->paginate(10);
        $clients = Client::all();

        return view('proforma-client.list', compact('proformas', 'clients'));
    }

    public function create()
    {
        $clients = Client::all();
        $magasins = Magasin::with('site.entite')->get();
        $articles = Article::with('unite')->get();

        return view('proforma-client.create', compact('clients', 'magasins', 'articles'));
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

            $proforma = ProformaClient::create([
                'date_' => $request->date_,
                'id_client' => $request->id_client,
                'id_magasin' => $request->id_magasin,
                'description' => $request->description,
                'etat' => 1, // Créée
            ]);

            foreach ($request->articles as $art) {
                ProformaClientFille::create([
                    'id_proforma_client' => $proforma->id_proforma_client,
                    'id_article' => $art['id_article'],
                    'quantite' => $art['quantite'],
                    'prix' => $art['prix'],
                ]);
            }

            DB::commit();
            return redirect()->route('proforma-client.list')->with('success', 'Proforma client enregistrée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $proforma = ProformaClient::with(['proformaClientFille.article.unite', 'client', 'magasin.site.entite'])->findOrFail($id);
        return view('proforma-client.show', compact('proforma'));
    }

    public function changeEtat($id, Request $request)
    {
        $proforma = ProformaClient::findOrFail($id);
        $proforma->update(['etat' => $request->etat]);
        return back()->with('success', 'État de la proforma mis à jour.');
    }

    public function exportPdf($id)
    {
        $proforma = ProformaClient::with(['proformaClientFille.article.unite', 'client'])->findOrFail($id);
        
        $pdf = Pdf::loadView('proforma-client.pdf', compact('proforma'));
        return $pdf->stream('proforma_client_' . $proforma->id_proforma_client . '.pdf');
    }

    public function destroy($id)
    {
        $proforma = ProformaClient::findOrFail($id);
        $proforma->delete();
        return redirect()->route('proforma-client.list')->with('success', 'Proforma supprimée.');
    }
}
