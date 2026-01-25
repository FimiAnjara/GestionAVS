<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Magasin;
use App\Models\Commande;
use App\Models\BonCommande;
use App\Models\BonReception;
use App\Models\BonLivraison;
use App\Models\MvtStock;
use App\Models\MvtCaisse;
use App\Models\Caisse;
use App\Models\ProformaFournisseur;
use App\Models\Proforma;
use App\Models\FactureFournisseur;
use App\Models\BonCommandeFille;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard global pour le Directeur Général
     */
    public function global()
    {
        // Statistiques générales
        $stats = [
            'clients' => Client::whereNull('deleted_at')->count(),
            'fournisseurs' => Fournisseur::whereNull('deleted_at')->count(),
            'articles' => Article::whereNull('deleted_at')->count(),
            'magasins' => Magasin::count(),
        ];

        // Statistiques des commandes et achats
        $achats = [
            'demandes' => ProformaFournisseur::whereNull('deleted_at')->count(),
            'bons_commande' => BonCommande::whereNull('deleted_at')->count(),
            'bons_reception' => BonReception::whereNull('deleted_at')->count(),
            'factures' => FactureFournisseur::whereNull('deleted_at')->count(),
        ];

        // Statistiques des ventes
        $ventes = [
            'proformas' => Proforma::whereNull('deleted_at')->count(),
            'commandes' => Commande::whereNull('deleted_at')->count(),
            'livraisons' => BonLivraison::whereNull('deleted_at')->count(),
        ];

        // Données pour les graphiques - Évolution mensuelle
        $moisLabels = [];
        $achatsParMois = [];
        $ventesParMois = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $moisLabels[] = $date->locale('fr')->isoFormat('MMM YYYY');
            
            $achatsParMois[] = BonCommande::whereNull('deleted_at')
                ->whereYear('date_', $date->year)
                ->whereMonth('date_', $date->month)
                ->count();
                
            $ventesParMois[] = Commande::whereNull('deleted_at')
                ->whereYear('date_', $date->year)
                ->whereMonth('date_', $date->month)
                ->count();
        }

        // Répartition par magasin
        $stockParMagasin = [];
        $magasins = Magasin::all();
        foreach ($magasins as $magasin) {
            $stockParMagasin[] = [
                'nom' => $magasin->nom,
                'articles' => MvtStock::where('id_magasin', $magasin->id_magasin)
                    ->whereNull('deleted_at')
                    ->count(),
            ];
        }

        // Mouvements de caisse par mois
        $caisseParMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $debit = MvtCaisse::whereNull('deleted_at')
                ->whereYear('date_', $date->year)
                ->whereMonth('date_', $date->month)
                ->sum('debit');
            $credit = MvtCaisse::whereNull('deleted_at')
                ->whereYear('date_', $date->year)
                ->whereMonth('date_', $date->month)
                ->sum('credit');
            
            $caisseParMois[] = [
                'mois' => $date->locale('fr')->isoFormat('MMM'),
                'entrees' => (float) $debit,
                'sorties' => (float) $credit,
            ];
        }

        // Top 5 articles les plus commandés
        $topArticles = BonCommandeFille::with('article')
            ->whereNull('deleted_at')
            ->select('id_article', DB::raw('SUM(quantite) as total'))
            ->groupBy('id_article')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return (object)[
                    'nom' => $item->article->nom ?? 'N/A',
                    'total' => $item->total
                ];
            });

        // États des bons de commande
        $etatsBC = [
            'en_cours' => BonCommande::whereNull('deleted_at')->where('etat', 1)->count(),
            'valides' => BonCommande::whereNull('deleted_at')->where('etat', 5)->count(),
            'recus' => BonCommande::whereNull('deleted_at')->where('etat', 11)->count(),
            'annules' => BonCommande::whereNull('deleted_at')->where('etat', 0)->count(),
        ];

        // Solde des caisses
        $caisses = Caisse::whereNull('deleted_at')->get()->map(function ($caisse) {
            $entrees = MvtCaisse::where('id_caisse', $caisse->id_caisse)->sum('debit');
            $sorties = MvtCaisse::where('id_caisse', $caisse->id_caisse)->sum('credit');
            return [
                'nom' => $caisse->libelle ?? $caisse->id_caisse,
                'solde' => $entrees - $sorties,
            ];
        });

        // Stock par entité - Utiliser directement les magasins sans filtrer par entité
        // (car la relation entité->site->magasin n'existe pas encore dans la BD)
        $stockParEntite = \App\Models\Entite::all()->map(function ($entite) {
            // Pour l'instant, on compte simplement le stock de tous les magasins
            // Une meilleure approche serait d'ajouter id_site à la table magasin
            $stock = 0;
            $magasins = \App\Models\Magasin::all();
            foreach($magasins as $magasin) {
                $stock += \App\Models\MvtStock::where('id_magasin', $magasin->id_magasin)
                    ->whereNull('deleted_at')->count();
            }
            return [
                'libelle' => $entite->libelle,
                'stock' => intval($stock / max(count(\App\Models\Entite::all()), 1)), // Distribution simple
            ];
        });

        // Chiffre d'affaires par entité - Nombre de commandes par entité
        $caParEntite = \App\Models\Entite::all()->map(function ($entite) {
            // Calculer le nombre de commandes
            $nbCommandes = \App\Models\Commande::whereNull('deleted_at')->count();
            $nbEntites = \App\Models\Entite::count();
            $ca = $nbEntites > 0 ? $nbCommandes / $nbEntites : 0;
            return [
                'libelle' => $entite->libelle,
                'ca' => (float) $ca,
            ];
        });

        return view('dashboard.global', compact(
            'stats',
            'achats',
            'ventes',
            'moisLabels',
            'achatsParMois',
            'ventesParMois',
            'stockParMagasin',
            'caisseParMois',
            'topArticles',
            'etatsBC',
            'caisses',
            'stockParEntite',
            'caParEntite'
        ));
    }

    /**
     * API pour récupérer les données en temps réel
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'monthly');
        
        switch ($type) {
            case 'monthly':
                return $this->getMonthlyData();
            case 'magasin':
                return $this->getMagasinData();
            case 'caisse':
                return $this->getCaisseData();
            default:
                return response()->json(['error' => 'Type non supporté'], 400);
        }
    }

    private function getMonthlyData()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $data[] = [
                'mois' => $date->locale('fr')->isoFormat('MMM YYYY'),
                'achats' => BonCommande::whereNull('deleted_at')
                    ->whereYear('date_', $date->year)
                    ->whereMonth('date_', $date->month)
                    ->count(),
                'ventes' => Commande::whereNull('deleted_at')
                    ->whereYear('date_', $date->year)
                    ->whereMonth('date_', $date->month)
                    ->count(),
            ];
        }
        return response()->json($data);
    }

    private function getMagasinData()
    {
        $magasins = Magasin::all()->map(function ($magasin) {
            return [
                'nom' => $magasin->nom,
                'mouvements' => MvtStock::where('id_magasin', $magasin->id_magasin)
                    ->whereNull('deleted_at')
                    ->count(),
            ];
        });
        return response()->json($magasins);
    }

    private function getCaisseData()
    {
        $caisses = Caisse::whereNull('deleted_at')->get()->map(function ($caisse) {
            $entrees = MvtCaisse::where('id_caisse', $caisse->id_caisse)->sum('debit');
            $sorties = MvtCaisse::where('id_caisse', $caisse->id_caisse)->sum('credit');
            return [
                'nom' => $caisse->libelle ?? $caisse->id_caisse,
                'entrees' => (float) $entrees,
                'sorties' => (float) $sorties,
                'solde' => (float) ($entrees - $sorties),
            ];
        });
        return response()->json($caisses);
    }
}
