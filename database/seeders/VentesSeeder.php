<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Ventes\BonCommandeClient;
use App\Models\Ventes\BonCommandeClientFille;
use Illuminate\Database\Seeder;

class VentesSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🛍️ Création des données de ventes (Bons de Commande Client)...');

        $clients = Client::all();
        $articles = Article::all();
        $magasins = Magasin::all();

        if ($clients->isEmpty() || $articles->isEmpty() || $magasins->isEmpty()) {
            $this->command->error('❌ Erreur: Assurez-vous que les clients, articles et magasins existent');
            return;
        }

        $startDate = now()->subMonths(6);
        $bccCount = 0;

        // Créer 25 bons de commande clients
        for ($i = 1; $i <= 25; $i++) {
            $client = $clients->random();
            $magasin = $magasins->random();
            
            $bcc = BonCommandeClient::create([
                'date_' => $startDate->clone()->addDays(rand(0, 180))->format('Y-m-d'),
                'id_client' => $client->id_client,
                'id_magasin' => $magasin->id_magasin,
                'description' => null,
                'id_proforma_client' => null,
                'etat' => rand(1, 3), // 1: Créée, 2: Confirmée, 3: Expédiée
            ]);

            // Ajouter 2-5 articles par bon de commande
            $nbArticles = rand(2, 5);
            $articlesSelectionnés = $articles->random($nbArticles);

            foreach ($articlesSelectionnés as $index => $article) {
                $quantite = rand(1, 10);
                $prix = $article->articleFille?->first()?->prix ?? rand(5000, 100000);
                
                BonCommandeClientFille::create([
                    'id_article' => $article->id_article,
                    'quantite' => $quantite,
                    'prix' => $prix,
                    'id_bon_commande_client' => $bcc->id_bon_commande_client,
                ]);
            }

            $bccCount++;
            $this->command->line("  ✓ Bon créé: {$bcc->id_bon_commande_client} pour {$client->nom}");
        }

        $this->command->info("✅ {$bccCount} bons de commande clients créés avec succès!");
    }
}
