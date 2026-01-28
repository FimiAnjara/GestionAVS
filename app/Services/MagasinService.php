<?php

namespace App\Services;

use App\Models\Magasin;
use App\Models\Article;
use App\Models\MvtStockFille;
use App\Models\Site;
use Illuminate\Support\Facades\DB;

class MagasinService
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Calculer la valeur totale du stock d'un magasin en Ariary
     * selon la méthode d'évaluation de chaque article (CMUP, FIFO, LIFO)
     *
     * @param string $idMagasin
     * @return array ['valeur_totale' => float, 'details' => array]
     */
    public function getValeurStockParMethode(string $idMagasin): array
    {
        $magasin = Magasin::with('site')->findOrFail($idMagasin);
        
        // Récupérer tous les articles avec du stock dans ce magasin
        $articlesEnStock = MvtStockFille::whereHas('mvtStock', function($q) use ($idMagasin) {
                $q->where('id_magasin', $idMagasin)->whereNull('deleted_at');
            })
            ->where('reste', '>', 0)
            ->whereNull('deleted_at')
            ->select('id_article')
            ->distinct()
            ->pluck('id_article');
        
        $valeurTotale = 0;
        $details = [];

        foreach ($articlesEnStock as $idArticle) {
            $article = Article::with('typeEvaluation', 'unite')->find($idArticle);
            if (!$article) continue;

            // Obtenir la méthode d'évaluation de l'article
            $methodeEvaluation = $article->typeEvaluation->id_type_evaluation_stock ?? 'CMUP';
            
            // Récupérer tous les lots disponibles pour cet article
            $batches = $this->getBatchesDisponibles($idMagasin, $idArticle);
            $quantiteTotale = $batches->sum('reste');
            
            if ($quantiteTotale <= 0) continue;

            // Calculer la valeur selon la méthode d'évaluation
            $valeurArticle = $this->calculerValeurParMethode($batches, $quantiteTotale, $methodeEvaluation);
            
            $details[] = [
                'article' => $article,
                'methode' => $methodeEvaluation,
                'quantite' => $quantiteTotale,
                'valeur' => $valeurArticle,
                'prix_unitaire' => $quantiteTotale > 0 ? $valeurArticle / $quantiteTotale : 0,
            ];
            
            $valeurTotale += $valeurArticle;
        }

        return [
            'magasin' => $magasin,
            'valeur_totale' => $valeurTotale,
            'details' => $details,
        ];
    }

    /**
     * Calculer la valeur du stock selon la méthode d'évaluation
     *
     * @param \Illuminate\Support\Collection $batches
     * @param float $quantiteTotale
     * @param string $methode CMUP, FIFO, LIFO
     * @return float
     */
    protected function calculerValeurParMethode($batches, float $quantiteTotale, string $methode): float
    {
        $methode = strtoupper($methode);

        return match($methode) {
            'CMUP' => $this->calculerValeurCMUP($batches),
            'FIFO' => $this->calculerValeurFIFO($batches),
            'LIFO' => $this->calculerValeurLIFO($batches),
            default => $this->calculerValeurCMUP($batches),
        };
    }

    /**
     * Calculer la valeur selon CMUP (Coût Moyen Unitaire Pondéré)
     * Valeur = somme(reste * prix_unitaire) de tous les lots
     */
    protected function calculerValeurCMUP($batches): float
    {
        return $batches->sum(function($batch) {
            return floatval($batch->reste) * floatval($batch->prix_unitaire);
        });
    }

    /**
     * Calculer la valeur selon FIFO (Premier Entré, Premier Sorti)
     * On valorise le stock restant aux prix des dernières entrées (les plus récentes)
     */
    protected function calculerValeurFIFO($batches): float
    {
        // Trier par date d'entrée décroissante (les plus récents d'abord pour valoriser)
        $sortedBatches = $batches->sortByDesc('created_at');
        
        return $sortedBatches->sum(function($batch) {
            return floatval($batch->reste) * floatval($batch->prix_unitaire);
        });
    }

    /**
     * Calculer la valeur selon LIFO (Dernier Entré, Premier Sorti)
     * On valorise le stock restant aux prix des premières entrées (les plus anciennes)
     */
    protected function calculerValeurLIFO($batches): float
    {
        // Trier par date d'entrée croissante (les plus anciens d'abord pour valoriser)
        $sortedBatches = $batches->sortBy('created_at');
        
        return $sortedBatches->sum(function($batch) {
            return floatval($batch->reste) * floatval($batch->prix_unitaire);
        });
    }

    /**
     * Récupérer les lots disponibles pour un article dans un magasin
     */
    protected function getBatchesDisponibles(string $idMagasin, string $idArticle)
    {
        return MvtStockFille::whereHas('mvtStock', function($q) use ($idMagasin) {
                $q->where('id_magasin', $idMagasin)->whereNull('deleted_at');
            })
            ->where('id_article', $idArticle)
            ->where('entree', '>', 0) // Seulement les entrées
            ->where('reste', '>', 0)   // Avec stock disponible
            ->whereNull('deleted_at')
            ->get();
    }

    /**
     * Obtenir la valeur des stocks de tous les magasins
     *
     * @return array [['magasin' => Magasin, 'valeur' => float], ...]
     */
    public function getValeurStockTousMagasins(): array
    {
        $magasins = Magasin::with('site.entite')->whereNull('deleted_at')->get();
        $result = [];

        foreach ($magasins as $magasin) {
            $evaluation = $this->getValeurStockParMethode($magasin->id_magasin);
            $result[] = [
                'magasin' => $magasin,
                'valeur' => $evaluation['valeur_totale'],
                'nb_articles' => count($evaluation['details']),
            ];
        }

        return $result;
    }

    /**
     * Obtenir la valeur des stocks groupée par entité
     *
     * @return array [['entite' => Entite, 'valeur' => float, 'magasins' => [...]], ...]
     */
    public function getValeurStockParEntite(): array
    {
        $siteIds = Site::pluck('id_site', 'id_entite');
        $magasins = Magasin::with('site.entite')->whereNull('deleted_at')->get();
        
        $parEntite = [];

        foreach ($magasins as $magasin) {
            $idEntite = $magasin->site->id_entite ?? 'sans_entite';
            $entite = $magasin->site->entite ?? null;
            
            if (!isset($parEntite[$idEntite])) {
                $parEntite[$idEntite] = [
                    'entite' => $entite,
                    'valeur' => 0,
                    'magasins' => [],
                ];
            }

            $evaluation = $this->getValeurStockParMethode($magasin->id_magasin);
            $parEntite[$idEntite]['valeur'] += $evaluation['valeur_totale'];
            $parEntite[$idEntite]['magasins'][] = [
                'magasin' => $magasin,
                'valeur' => $evaluation['valeur_totale'],
            ];
        }

        return array_values($parEntite);
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
