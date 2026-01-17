<?php

namespace App\Http\Controllers;

use App\Models\MvtStock;
use App\Models\Article;
use App\Models\Emplacement;
use App\Models\BonCommande;
use App\Models\BonReception;
use Illuminate\Http\Request;

class MvtStockController extends Controller
{
    /**
     * Afficher la liste des mouvements de stock
     */
    public function list(Request $request)
    {
        $query = MvtStock::query();
        
        if ($request->filled('date_from')) {
            $query->where('date_', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date_', '<=', $request->date_to);
        }
        
        if ($request->filled('id')) {
            $query->where('id_mvt_stock', 'like', '%' . $request->id . '%');
        }
        
        if ($request->filled('id_article')) {
            $query->where('id_article', $request->id_article);
        }
        
        $mouvements = $query->latest('date_')->paginate(10);
        $articles = Article::all();
        
        return view('mvt-stock.list', compact('mouvements', 'articles'));
    }
    
    /**
     * Afficher les détails
     */
    public function show($id)
    {
        $mouvement = MvtStock::findOrFail($id);
        
        return view('mvt-stock.show', compact('mouvement'));
    }

    /**
     * Exporter en PDF
     */
    public function exportPdf($id)
    {
        $mouvement = MvtStock::findOrFail($id);
        
        $pdf = \PDF::loadView('mvt-stock.pdf', compact('mouvement'));
        return $pdf->download($mouvement->id_mvt_stock . '.pdf');
    }
}
