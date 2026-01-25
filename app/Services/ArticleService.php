<?php

namespace App\Services;

use App\Models\MvtStockFille;
use Illuminate\Support\Facades\DB;

class ArticleService
{
    /**
     * Obtenir le prix unitaire actuel d'un article dans un magasin
     * selon la méthode d'évaluation spécifiée
     *
     * @param string $idArticle
     * @param string $idMagasin
     * @param string $methodeEvaluation 'CMUP', 'FIFO', ou 'LIFO'
     * @return float
     */
    public function getPrixUnitaireActuel(string $idArticle, string $idMagasin, string $methodeEvaluation = 'CMUP'): float
    {
        $methode = strtoupper($methodeEvaluation);

        return match($methode) {
            'CMUP' => $this->calculateCMUP($idArticle, $idMagasin),
            'FIFO' => $this->calculateFIFO($idArticle, $idMagasin),
            'LIFO' => $this->calculateLIFO($idArticle, $idMagasin),
            default => $this->calculateCMUP($idArticle, $idMagasin),
        };
    }

    /**
     * Calculer le Coût Moyen Unitaire Pondéré (CMUP)
     * Formule: Sum(prix_unitaire * reste) / Sum(reste)
     */
    protected function calculateCMUP(string $idArticle, string $idMagasin): float
    {
        $batches = $this->getAvailableBatches($idArticle, $idMagasin);

        if ($batches->isEmpty()) {
            return 0.0;
        }

        $totalValue = 0;
        $totalQuantity = 0;

        foreach ($batches as $batch) {
            $totalValue += $batch->prix_unitaire * $batch->reste;
            $totalQuantity += $batch->reste;
        }

        return $totalQuantity > 0 ? $totalValue / $totalQuantity : 0.0;
    }

    /**
     * Calculer le prix selon FIFO (First In First Out)
     * Retourne le prix du lot le plus ancien avec du stock disponible
     */
    protected function calculateFIFO(string $idArticle, string $idMagasin): float
    {
        $batch = $this->getAvailableBatches($idArticle, $idMagasin)
            ->sortBy([
                ['created_at', 'asc'],
                ['id_mvt_stock_fille', 'asc']
            ])
            ->first();

        return $batch ? $batch->prix_unitaire : 0.0;
    }

    /**
     * Calculer le prix selon LIFO (Last In First Out)
     * Retourne le prix du lot le plus récent avec du stock disponible
     */
    protected function calculateLIFO(string $idArticle, string $idMagasin): float
    {
        $batch = $this->getAvailableBatches($idArticle, $idMagasin)
            ->sortBy([
                ['created_at', 'desc'],
                ['id_mvt_stock_fille', 'desc']
            ])
            ->first();

        return $batch ? $batch->prix_unitaire : 0.0;
    }

    /**
     * Récupérer tous les lots d'entrée disponibles (avec reste > 0)
     * pour un article dans un magasin donné
     */
    protected function getAvailableBatches(string $idArticle, string $idMagasin)
    {
        return MvtStockFille::whereHas('mvtStock', function($q) use ($idMagasin) {
                $q->where('id_magasin', $idMagasin);
            })
            ->where('id_article', $idArticle)
            ->where('entree', '>', 0)  // Seulement les entrées
            ->where('reste', '>', 0)    // Seulement les lots avec stock disponible
            ->get();
    }
}
