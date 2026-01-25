<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\BonCommande;
use App\Models\BonCommandeFille;
use App\Models\Commande;
use App\Models\CommandeFille;
use App\Models\MvtStock;
use App\Models\MvtStockFille;
use App\Models\ProformaFournisseur;
use App\Models\ProformaFournisseurFille;
use Carbon\Carbon;

class DashboardTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les articles existants
        $articles = Article::whereNull('deleted_at')->get();
        if ($articles->isEmpty()) {
            echo "⚠️  Aucun article trouvé dans la base de données.\n";
            return;
        }

        // 0. Créer d'abord les ProformaFournisseur (dépendance pour BonCommande)
        $proformas = [];
        for ($i = 0; $i < 24; $i++) {
            $proformaId = 'PROF_FOURN_' . str_pad($i+1, 3, '0', STR_PAD_LEFT);
            
            if (!ProformaFournisseur::find($proformaId)) {
                $proforma = ProformaFournisseur::create([
                    'id_proformaFournisseur' => $proformaId,
                    'date_' => Carbon::now()->subDays(rand(10, 365)),
                    'etat' => 1,
                    'id_fournisseur' => 'FOURN_001',
                    'id_magasin' => 'MAG_001',
                    'id_utilisateur' => 'user_001',
                ]);
                
                // Ajouter une ligne de proforma
                ProformaFournisseurFille::create([
                    'id_proformaFornisseurFille' => $proformaId . '_1',
                    'id_proformaFournisseur' => $proformaId,
                    'id_article' => $articles->random()->id_article,
                    'quantite' => rand(5, 50),
                    'prix_achat' => rand(10000, 100000),
                ]);
                
                $proformas[] = $proformaId;
            }
        }

        // 1. Créer des Bons de Commande (12 mois)
        $bcCount = 0;
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            for ($j = 0; $j < 2; $j++) {
                $bonId = 'BC_TEST_' . $date->format('Ym') . '_' . ($j+1);
                
                if (!BonCommande::find($bonId)) {
                    $proformaIndex = rand(0, count($proformas) - 1);
                    
                    $bon = BonCommande::create([
                        'id_bonCommande' => $bonId,
                        'date_' => $date->addDays(rand(1, 28)),
                        'etat' => rand(1, 11),
                        'id_magasin' => 'MAG_001',
                        'id_utilisateur' => 'user_001',
                        'id_proformaFournisseur' => $proformas[$proformaIndex],
                    ]);

                    // Ajouter des lignes
                    for ($k = 0; $k < 2; $k++) {
                        BonCommandeFille::create([
                            'id_bonCommandeFille' => $bonId . '_' . ($k+1),
                            'quantite' => rand(5, 30),
                            'id_bonCommande' => $bonId,
                            'id_article' => $articles->random()->id_article,
                        ]);
                    }
                    $bcCount++;
                }
            }
        }

        // 2. Créer des Commandes Clients (12 mois)
        $cmdCount = 0;
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            for ($j = 0; $j < 3; $j++) {
                $cmdId = 'CMD_TEST_' . $date->format('Ym') . '_' . ($j+1);
                
                if (!Commande::find($cmdId)) {
                    $cmd = Commande::create([
                        'id_commande' => $cmdId,
                        'date_' => $date->addDays(rand(1, 28)),
                        'etat' => rand(1, 11),
                        'id_utilisateur' => 'user_004',
                        'id_client' => $j % 2 == 0 ? 'CLI_001' : 'CLI_002',
                        'id_magasin' => ['MAG_001', 'MAG_002', 'MAG_003'][rand(0, 2)],
                    ]);

                    // Ajouter des lignes de commande
                    for ($k = 0; $k < 2; $k++) {
                        CommandeFille::create([
                            'id_commandeFille' => $cmdId . '_' . ($k+1),
                            'quantite' => rand(1, 10),
                            'id_unite' => 'unit_001',
                            'id_commande' => $cmdId,
                            'id_article' => $articles->random()->id_article,
                        ]);
                    }
                    $cmdCount++;
                }
            }
        }

        // 3. Créer des mouvements de stock (6 mois)
        $mvtCount = 0;
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            for ($j = 0; $j < 4; $j++) {
                $mvtId = 'MVT_TEST_' . $date->format('Ym') . '_' . ($j+1);
                
                if (!MvtStock::find($mvtId)) {
                    $mvt = MvtStock::create([
                        'id_mvt_stock' => $mvtId,
                        'date_' => $date->addDays(rand(1, 28)),
                        'id_magasin' => ['MAG_001', 'MAG_002', 'MAG_003'][rand(0, 2)],
                        'montant_total' => rand(100000, 1000000),
                        'description' => 'Mouvement de stock test',
                        'id_utilisateur' => 'user_002',
                    ]);

                    // Ajouter des lignes de mouvement
                    for ($k = 0; $k < 2; $k++) {
                        MvtStockFille::create([
                            'id_mvt_stock_fille' => $mvtId . '_' . ($k+1),
                            'id_mvt_stock' => $mvtId,
                            'id_article' => $articles->random()->id_article,
                            'entree' => rand(10, 100),
                            'sortie' => 0,
                        ]);
                    }
                    $mvtCount++;
                }
            }
        }

        echo "✅ Données de test créées avec succès!\n";
        echo "   - 24 Proformas Fournisseur\n";
        echo "   - $bcCount Bons de Commande (2 par mois × 12 mois)\n";
        echo "   - $cmdCount Commandes Clients (3 par mois × 12 mois)\n";
        echo "   - $mvtCount Mouvements de Stock (4 par mois × 6 mois)\n";
        echo "\n📊 Le dashboard devrait afficher:\n";
        echo "   ✓ Évolution Achats/Ventes sur 12 mois\n";
        echo "   ✓ Répartition des états de BC\n";
        echo "   ✓ Mouvements de caisse\n";
        echo "   ✓ Stock par magasin\n";
        echo "   ✓ Top articles commandés\n";
    }
}

