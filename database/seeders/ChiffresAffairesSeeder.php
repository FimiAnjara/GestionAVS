<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commande;
use App\Models\CommandeFille;
use App\Models\Article;
use App\Models\Client;
use Carbon\Carbon;

class ChiffresAffairesSeeder extends Seeder
{
    public function run(): void
    {
        $articles = Article::whereNull('deleted_at')->get();
        $clients = Client::whereNull('deleted_at')->get();

        if ($articles->isEmpty() || $clients->isEmpty()) {
            echo "⚠️  Articles ou clients manquants.\n";
            return;
        }

        // Récupérer une unité (la première disponible)
        $unite = \App\Models\Unite::first();
        if (!$unite) {
            echo "⚠️  Aucune unité trouvée dans la base.\n";
            return;
        }

        // Générer des commandes pour 12 mois
        $mois = [];
        for ($i = 11; $i >= 0; $i--) {
            $mois[] = Carbon::now()->subMonths($i);
        }

        $totalChiffre = 0;
        $compteur = 0;

        foreach ($mois as $date) {
            $nbCommandes = rand(3, 5);
            
            for ($j = 0; $j < $nbCommandes; $j++) {
                $compteur++;
                $commandeId = 'CMD_' . $date->format('Ym') . '_' . str_pad($j + 1, 3, '0', STR_PAD_LEFT);
                $client = $clients->random();
                
                $commande = Commande::firstOrCreate(
                    ['id_commande' => $commandeId],
                    [
                        'date_' => $date->addDays(rand(0, 28)),
                        'etat' => 3,
                        'id_client' => $client->id_client,
                        'id_utilisateur' => 'user_013',
                    ]
                );

                $nbArticles = rand(3, 8);
                $montantTotal = 0;

                for ($k = 0; $k < $nbArticles; $k++) {
                    $article = $articles->random();
                    $quantite = rand(2, 20);
                    $prixUnitaire = rand(50000, 500000);
                    $montantLigne = $quantite * $prixUnitaire;
                    $montantTotal += $montantLigne;

                    CommandeFille::firstOrCreate(
                        ['id_commandeFille' => $commandeId . '_' . str_pad($k + 1, 2, '0', STR_PAD_LEFT)],
                        [
                            'id_commande' => $commandeId,
                            'id_article' => $article->id_article,
                            'quantite' => $quantite,
                            'id_unite' => $unite->id_unite,
                        ]
                    );
                }

                $totalChiffre += $montantTotal;
                echo "✅ [{$compteur}] Commande: $commandeId | {$client->nom_client} | " . number_format($montantTotal, 0, '.', ' ') . "\n";
            }
        }

        echo "\n✅ ✅ ✅ Données créées avec succès!\n";
        echo "📊 Total commandes: {$compteur}\n";
        echo "💰 Chiffre d'affaires total: " . number_format($totalChiffre, 0, '.', ' ') . "\n";
    }
}
