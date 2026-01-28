<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Article;
use App\Models\Magasin;
use App\Models\BonCommande;
use App\Models\BonReception;
use App\Models\BonLivraison;
use App\Models\MvtStock;
use App\Models\MvtStockFille;
use App\Models\MvtCaisse;
use App\Models\Caisse;
use App\Models\ProformaFournisseur;
use App\Models\Proforma;
use App\Models\FactureFournisseur;
use App\Models\BonCommandeFille;
use App\Models\Ventes\BonCommandeClient;
use App\Services\MagasinService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\Site;

class DashboardController extends Controller
{
    protected $magasinService;

    public function __construct(MagasinService $magasinService)
    {
        $this->magasinService = $magasinService;
    }

    /**
     * Dashboard global pour le Directeur Général - Version simple
     */
    public function globalSimple()
    {
        try {
            // Statistiques générales de base uniquement
            $stats = [
                'clients' => Client::whereNull('deleted_at')->count(),
                'fournisseurs' => Fournisseur::whereNull('deleted_at')->count(),
                'articles' => Article::whereNull('deleted_at')->count(),
                'magasins' => Magasin::count(),
            ];

            // Statistiques des commandes et achats de base
            $achats = [
                'demandes' => ProformaFournisseur::whereNull('deleted_at')->count(),
                'bons_commande' => BonCommande::whereNull('deleted_at')->count(),
                'bons_reception' => BonReception::whereNull('deleted_at')->count(),
                'factures' => FactureFournisseur::whereNull('deleted_at')->count(),
            ];

            // Statistiques des ventes de base
            $ventes = [
                'proformas' => Proforma::whereNull('deleted_at')->count(),
                'commandes' => BonCommandeClient::whereNull('deleted_at')->count(),
                'livraisons' => BonLivraison::whereNull('deleted_at')->count(),
            ];

            // Données simplifiées pour les graphiques
            $moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'];
            $achatsParMois = [10, 15, 12, 8, 20, 25];
            $ventesParMois = [8, 12, 18, 15, 22, 30];

            // Répartition par magasin (statique pour test)
            $stockParMagasin = [
                ['nom' => 'Magasin Principal', 'articles' => 150],
                ['nom' => 'Magasin Secondaire', 'articles' => 80],
            ];

            // Mouvements de caisse (statique pour test)
            $caisseParMois = [
                ['mois' => 'Jan', 'entrees' => 50000, 'sorties' => 45000],
                ['mois' => 'Fév', 'entrees' => 60000, 'sorties' => 55000],
                ['mois' => 'Mar', 'entrees' => 70000, 'sorties' => 65000],
            ];

            // Top articles (statique pour test)
            $topArticles = [
                (object)['nom' => 'Article 1', 'total' => 100],
                (object)['nom' => 'Article 2', 'total' => 85],
                (object)['nom' => 'Article 3', 'total' => 70],
            ];

            // États des bons de commande
            $etatsBC = [
                'en_cours' => BonCommande::whereNull('deleted_at')->where('etat', 1)->count(),
                'valides' => BonCommande::whereNull('deleted_at')->where('etat', 5)->count(),
                'recus' => BonCommande::whereNull('deleted_at')->where('etat', 11)->count(),
                'annules' => BonCommande::whereNull('deleted_at')->where('etat', 0)->count(),
            ];

            // Données simplifiées pour éviter timeout
            $stockParEntite = [
                ['libelle' => 'Entité 1', 'valeur' => 250000],
                ['libelle' => 'Entité 2', 'valeur' => 180000],
            ];

            $caParEntite = [
                ['libelle' => 'Entité 1', 'ca' => 500000],
                ['libelle' => 'Entité 2', 'ca' => 350000],
            ];

            // Caisses simplifiées
            $caisses = [
                ['nom' => 'Caisse Principale', 'solde' => 125000],
                ['nom' => 'Caisse Secondaire', 'solde' => 75000],
            ];

            // Bons récents (limités)
            $bonsAchatRecents = BonCommande::whereNull('deleted_at')
                ->latest('date_')
                ->limit(3)
                ->get();

            $bonsVenteRecents = BonCommandeClient::whereNull('deleted_at')
                ->latest('date_')
                ->limit(3)
                ->get();

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
                'caParEntite',
                'bonsAchatRecents',
                'bonsVenteRecents'
            ));

        } catch (\Exception $e) {
            \Log::error('Erreur dashboard global simple: ' . $e->getMessage());
            
            return view('dashboard.global', [
                'error' => 'Une erreur est survenue lors du chargement du dashboard. Détails: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Dashboard global pour le Directeur Général
     */
    public function global()
    {
        try {
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
                'commandes' => BonCommandeClient::whereNull('deleted_at')->count(),
                'livraisons' => BonLivraison::whereNull('deleted_at')->count(),
            ];

            // Données pour les graphiques - Évolution mensuelle (limitée à 6 mois)
            $moisLabels = [];
            $achatsParMois = [];
            $ventesParMois = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $moisLabels[] = $date->locale('fr')->isoFormat('MMM YYYY');
                
                $achatsParMois[] = BonCommande::whereNull('deleted_at')
                    ->whereYear('date_', $date->year)
                    ->whereMonth('date_', $date->month)
                    ->count();
                    
                $ventesParMois[] = BonCommandeClient::whereNull('deleted_at')
                    ->whereYear('date_', $date->year)
                    ->whereMonth('date_', $date->month)
                    ->count();
            }

            // Répartition par magasin avec valeur du stock en Ariary (selon méthode d'évaluation)
            $stockParMagasin = [];
            try {
                $valeursStock = $this->magasinService->getValeurStockTousMagasins();
                $stockParMagasin = array_map(function($item) {
                    return [
                        'nom' => $item['magasin']->nom,
                        'valeur' => $item['valeur'],
                        'nb_articles' => $item['nb_articles'],
                    ];
                }, $valeursStock);
            } catch (\Exception $e) {
                \Log::warning('Erreur calcul stock par magasin: ' . $e->getMessage());
                $stockParMagasin = [];
            }

            // Mouvements de caisse par mois (limité à 6 mois)
            $caisseParMois = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $debit = MvtCaisse::whereNull('deleted_at')
                    ->whereYear('date_', $date->year)
                    ->whereMonth('date_', $date->month)
                    ->sum('debit') ?? 0;
                $credit = MvtCaisse::whereNull('deleted_at')
                    ->whereYear('date_', $date->year)
                    ->whereMonth('date_', $date->month)
                    ->sum('credit') ?? 0;
                
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

            // États des bons de commande (Achat + Vente)
            $etatsBC = [
                'en_cours' => BonCommande::whereNull('deleted_at')->where('etat', 1)->count() + 
                             BonCommandeClient::whereNull('deleted_at')->where('etat', 1)->count(),
                'valides' => BonCommande::whereNull('deleted_at')->where('etat', 5)->count() + 
                            BonCommandeClient::whereNull('deleted_at')->where('etat', 5)->count(),
                'recus' => BonCommande::whereNull('deleted_at')->where('etat', 11)->count() + 
                          BonCommandeClient::whereNull('deleted_at')->where('etat', 11)->count(),
                'annules' => BonCommande::whereNull('deleted_at')->where('etat', 0)->count() + 
                            BonCommandeClient::whereNull('deleted_at')->where('etat', 0)->count(),
            ];

            // Solde des caisses (simplifié)
            $caisses = Caisse::whereNull('deleted_at')
                ->select('id_caisse', 'libelle')
                ->get()
                ->map(function ($caisse) {
                    $entrees = MvtCaisse::where('id_caisse', $caisse->id_caisse)->sum('debit') ?? 0;
                    $sorties = MvtCaisse::where('id_caisse', $caisse->id_caisse)->sum('credit') ?? 0;
                    return [
                        'nom' => $caisse->libelle ?? $caisse->id_caisse,
                        'solde' => $entrees - $sorties,
                    ];
                })
                ->toArray();

            // Valeur du stock par entité (selon méthode d'évaluation de chaque article)
            $stockParEntite = [];
            try {
                $valeursParEntite = $this->magasinService->getValeurStockParEntite();
                $stockParEntite = array_map(function($item) {
                    return [
                        'libelle' => $item['entite']->nom ?? 'Sans entité',
                        'valeur' => $item['valeur'],
                    ];
                }, $valeursParEntite);
            } catch (\Exception $e) {
                \Log::warning('Erreur calcul stock par entité: ' . $e->getMessage());
                $stockParEntite = Entite::select('id_entite', 'nom')->get()->map(function ($entite) {
                    return [
                        'libelle' => $entite->nom,
                        'valeur' => 0,
                    ];
                })->toArray();
            }

            $caParEntite = Entite::select('id_entite', 'nom')->get()->map(function ($entite) {
                return [
                    'libelle' => $entite->nom,
                    'ca' => 0, // Temporaire pour éviter le timeout
                ];
            })->toArray();

            // Bons de commande d'achat récents
            $bonsAchatRecents = BonCommande::with('proformaFournisseur.fournisseur')
                ->whereNull('deleted_at')
                ->latest('date_')
                ->limit(5)
                ->get();

            // Bons de commande de vente récents
            $bonsVenteRecents = BonCommandeClient::with('client')
                ->whereNull('deleted_at')
                ->latest('date_')
                ->limit(5)
                ->get();

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
                'caParEntite',
                'bonsAchatRecents',
                'bonsVenteRecents'
            ));

        } catch (\Exception $e) {
            // En cas d'erreur, journaliser et afficher un message d'erreur
            \Log::error('Erreur dashboard global: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return view('dashboard.global', [
                'error' => 'Une erreur est survenue lors du chargement du dashboard. Détails: ' . $e->getMessage()
            ]);
        }
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
                'ventes' => BonCommandeClient::whereNull('deleted_at')
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
