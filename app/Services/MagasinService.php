<?php

namespace App\Services;

use App\Models\Magasin;
use App\Models\Article;
use App\Models\MvtStockFille;
use Illuminate\Support\Facades\DB;

class MagasinService
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Obtenir l'évaluation complète du stock d'un magasin
     * (Quantités, Prix actuels et Valeurs totales)
     *
     * @param string $idMagasin
     * @return array ['items' => [...], 'total_general' => float]
     */
    public function getEvaluationStockComplete(string $idMagasin): array
    {
        $magasin = Magasin::with('site')->findOrFail($idMagasin);
        
        // Récupérer les articles appartenant à l'entité du magasin
        $articles = Article::where('id_entite', $magasin->site->id_entite)
            ->with(['typeEvaluation', 'unite'])
            ->get();
        
        $items = [];
        $totalGeneral = 0;

        foreach ($articles as $article) {
            // Récupérer tous les lots disponibles pour cet article dans ce magasin
            $batches = MvtStockFille::whereHas('mvtStock', function($q) use ($idMagasin) {
                    $q->where('id_magasin', $idMagasin);
                })
                ->where('id_article', $article->id_article)
                ->where('reste', '>', 0)
                ->get();

            $stockActuel = $batches->sum('reste');

            if ($stockActuel > 0) {
                // Calculer la valeur totale en sommant chaque lot (Précision 100%)
                $valeurTotale = $batches->sum(function($batch) {
                    return $batch->reste * $batch->prix_unitaire;
                });

                // Le prix unitaire moyen pour l'affichage
                $prixMoyen = $valeurTotale / $stockActuel;
                
                $items[] = [
                    'article' => $article,
                    'quantite' => $stockActuel,
                    'prix_unitaire' => $prixMoyen,
                    'valeur_totale' => $valeurTotale
                ];
                
                $totalGeneral += $valeurTotale;
            }
        }

        return [
            'items' => $items,
            'total_general' => $totalGeneral
        ];
    }

    /**
     * Obtenir la valeur totale du stock pour plusieurs magasins en une seule fois (Optimisé)
     *
     * @param array $idMagasins
     * @return array Map [id_magasin => valeur_totale]
     */
    public function getValuationsBulk(array $idMagasins): array
    {
        if (empty($idMagasins)) {
            return [];
        }

        // Grouper par magasin et calculer la somme de (reste * prix_unitaire)
        $results = MvtStockFille::whereHas('mvtStock', function($q) use ($idMagasins) {
                $q->whereIn('id_magasin', $idMagasins);
            })
            ->where('reste', '>', 0)
            ->select('mvt_stock.id_magasin', DB::raw('SUM(reste * prix_unitaire) as valeur_totale'))
            ->join('mvt_stock', 'mvt_stock.id_mvt_stock', '=', 'mvt_stock_fille.id_mvt_stock')
            ->groupBy('mvt_stock.id_magasin')
            ->get();

        $valuationMap = [];
        // Initialiser avec 0 pour tous les magasins demandés
        foreach ($idMagasins as $id) {
            $valuationMap[$id] = 0;
        }

        foreach ($results as $row) {
            $valuationMap[$row->id_magasin] = (float) $row->valeur_totale;
        }

        return $valuationMap;
    }

    /**
     * Calculer uniquement la valeur monétaire totale du stock d'un magasin
     *
     * @param string $idMagasin
     * @return float
     */
    public function getValeurTotaleStock(string $idMagasin): float
    {
        $evaluation = $this->getValuationsBulk([$idMagasin]);
        return $evaluation[$idMagasin] ?? 0.0;
    }
}
