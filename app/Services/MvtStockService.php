<?php

namespace App\Services;

use App\Models\MvtStock;
use App\Models\MvtStockFille;
use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Exception;

class MvtStockService
{
    /**
     * Créer un mouvement de stock complet avec ses articles.
     *
     * @param array $data Données validées du mouvement
     * @return MvtStock
     * @throws Exception
     */
    public function createMovement(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Créer le mouvement parent
            $mvtStock = MvtStock::create([
                'id_mvt_stock' => $data['id_mvt_stock'],
                'date_' => $data['date_'],
                'id_magasin' => $data['id_magasin'],
                'id_type_mvt' => $data['id_type_mvt'],
                'description' => $data['description'] ?? null,
                'montant_total' => $data['montant_total'],
            ]);

            $isEntry = $this->isEntryMovement($data['id_type_mvt']);

            // 2. Créer les articles enfants (MvtStockFille)
            foreach ($data['articles'] as $index => $article) {
                if ($isEntry) {
                    // Mouvement d'entrée : simple enregistrement
                    $this->createEntryMovement($data['id_mvt_stock'], $index, $article);
                } else {
                    // Mouvement de sortie : allocation FEFO/FIFO
                    $this->createExitMovement($data['id_mvt_stock'], $data['id_magasin'], $index, $article);
                }
            }

            return $mvtStock;
        });
    }

    /**
     * Créer un mouvement d'entrée (simple enregistrement)
     */
    protected function createEntryMovement(string $idMvtStock, int $index, array $article): void
    {
        MvtStockFille::create([
            'id_mvt_stock_fille' => $idMvtStock . '_' . ($index + 1),
            'id_mvt_stock' => $idMvtStock,
            'id_article' => $article['id_article'],
            'id_mvt_source' => null, // Pas de source pour les entrées
            'entree' => $article['entree'] ?? 0,
            'sortie' => 0,
            'reste' => $article['entree'] ?? 0, // reste = entree pour les entrées
            'prix_unitaire' => $article['prix_unitaire'] ?? 0,
            'date_expiration' => $article['date_expiration'] ?? null,
        ]);
    }

    /**
     * Créer un mouvement de sortie avec allocation automatique
     */
    protected function createExitMovement(string $idMvtStock, string $idMagasin, int $index, array $article): void
    {
        $quantityNeeded = $article['sortie'] ?? 0;
        
        if ($quantityNeeded <= 0) {
            return;
        }

        // Vérifier la disponibilité
        if (!$this->checkAvailability($idMagasin, $article['id_article'], $quantityNeeded)) {
            throw new Exception("Stock insuffisant pour l'article {$article['id_article']}. Quantité demandée: {$quantityNeeded}");
        }

        // Récupérer l'article pour vérifier s'il est périssable et obtenir le prix actuel
        $articleModel = Article::with(['categorie', 'typeEvaluation'])->find($article['id_article']);
        $isPerishable = $articleModel?->categorie?->est_perissable ?? false;

        // Calculer le prix unitaire selon la méthode d'évaluation de l'article
        $articleService = app(\App\Services\ArticleService::class);
        $methodeEvaluation = $articleModel?->typeEvaluation?->id_type_evaluation_stock ?? 'CMUP';
        $prixUnitaireSortie = $articleService->getPrixUnitaireActuel($article['id_article'], $idMagasin, $methodeEvaluation);

        // Allouer le stock selon FEFO/FIFO/LIFO (pour déterminer d'où vient le stock physiquement)
        $allocations = $this->allocateStock($idMagasin, $article['id_article'], $quantityNeeded, $isPerishable, $methodeEvaluation);

        // Créer les mouvements de sortie et mettre à jour les sources
        foreach ($allocations as $allocationIndex => $allocation) {
            // Mettre à jour le reste de la source
            $this->updateSourceBatch($allocation['id_mvt_stock_fille'], $allocation['quantity_used']);

            // Créer le mouvement de sortie avec le prix selon la méthode d'évaluation
            MvtStockFille::create([
                'id_mvt_stock_fille' => $idMvtStock . '_' . ($index + 1) . '_' . ($allocationIndex + 1),
                'id_mvt_stock' => $idMvtStock,
                'id_article' => $article['id_article'],
                'id_mvt_source' => $allocation['id_mvt_stock_fille'], // Lien vers la source physique
                'entree' => 0,
                'sortie' => $allocation['quantity_used'],
                'reste' => 0, // Les sorties n'ont pas de reste
                'prix_unitaire' => ($methodeEvaluation === 'CMUP') ? $prixUnitaireSortie : $allocation['prix_unitaire'], // Prix selon méthode d'évaluation
                'date_expiration' => $allocation['date_expiration'],
            ]);
        }
    }

    /**
     * Vérifier si un mouvement est une entrée
     */
    protected function isEntryMovement(string $idTypeMvt): bool
    {
        // On considère que c'est une entrée si le libellé contient "entrée" ou "réception"
        $type = \App\Models\TypeMvtStock::find($idTypeMvt);
        if (!$type) {
            return false;
        }
        
        $libelle = strtolower($type->libelle);
        return str_contains($libelle, 'entrée') || 
               str_contains($libelle, 'entree') || 
               str_contains($libelle, 'réception') ||
               str_contains($libelle, 'reception');
    }

    /**
     * Vérifier la disponibilité du stock
     */
    protected function checkAvailability(string $idMagasin, string $idArticle, float $quantity): bool
    {
        $totalAvailable = MvtStockFille::whereHas('mvtStock', function($q) use ($idMagasin) {
                $q->where('id_magasin', $idMagasin);
            })
            ->where('id_article', $idArticle)
            ->where('reste', '>', 0)
            ->sum('reste');

        return $totalAvailable >= $quantity;
    }

    /**
     * Allouer le stock selon FEFO (périssable) ou FIFO (non-périssable)
     *
     * @return array Liste des allocations [['id_mvt_stock_fille' => ..., 'quantity_used' => ..., ...], ...]
     */
    protected function allocateStock(string $idMagasin, string $idArticle, float $quantityNeeded, bool $isPerishable, string $methodeEvaluation = 'FIFO'): array
    {
        // Récupérer les lots disponibles
        $query = MvtStockFille::with('mvtStock')
            ->whereHas('mvtStock', function($q) use ($idMagasin) {
                $q->where('id_magasin', $idMagasin);
            })
            ->where('id_article', $idArticle)
            ->where('reste', '>', 0);

        // Appliquer le tri selon la stratégie
        if ($isPerishable) {
            // FEFO : Priorité absolue à la date d'expiration (les plus proches en premier)
            $query->orderBy('date_expiration', 'asc')
                  ->orderBy('created_at', 'asc');
        } elseif ($methodeEvaluation === 'LIFO') {
            // LIFO : Dernier entré, premier sorti (pour les articles non périssables en mode LIFO)
            $query->orderBy('created_at', 'desc')
                  ->orderBy('id_mvt_stock_fille', 'desc');
        } else {
            // FIFO : Premier entré, premier sorti (Comportement par défaut)
            $query->orderBy('created_at', 'asc')
                  ->orderBy('id_mvt_stock_fille', 'asc');
        }

        $availableBatches = $query->get();

        $allocations = [];
        $remainingQuantity = $quantityNeeded;

        foreach ($availableBatches as $batch) {
            if ($remainingQuantity <= 0) {
                break;
            }

            $quantityToTake = min($batch->reste, $remainingQuantity);

            $allocations[] = [
                'id_mvt_stock_fille' => $batch->id_mvt_stock_fille,
                'quantity_used' => $quantityToTake,
                'prix_unitaire' => $batch->prix_unitaire,
                'date_expiration' => $batch->date_expiration,
            ];

            $remainingQuantity -= $quantityToTake;
        }

        if ($remainingQuantity > 0) {
            throw new Exception("Impossible d'allouer tout le stock demandé pour l'article {$idArticle}");
        }

        return $allocations;
    }

    /**
     * Mettre à jour le reste d'un lot source
     */
    protected function updateSourceBatch(string $idMvtStockFille, float $quantityUsed): void
    {
        $batch = MvtStockFille::findOrFail($idMvtStockFille);
        $batch->reste -= $quantityUsed;
        
        if ($batch->reste < 0) {
            throw new Exception("Erreur: le reste ne peut pas être négatif pour le lot {$idMvtStockFille}");
        }
        
        $batch->save();
    }
}
