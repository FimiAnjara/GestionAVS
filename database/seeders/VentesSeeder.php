<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Client;
use App\Models\Magasin;
use App\Models\Utilisateur;
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
        $utilisateurs = Utilisateur::all();

        if ($clients->isEmpty() || $articles->isEmpty() || $magasins->isEmpty()) {
            $this->command->error('❌ Erreur: Assurez-vous que les clients, articles et magasins existent');
            return;
        }

        $startDate = now()->subMonths(6);
        $bccCount = 0;

        // États possibles : 1=Créée, 5=Validée, 11=Reçue, 0=Annulée
        $etats = [1 => 'Créée', 5 => 'Validée', 11 => 'Expédiée', 0 => 'Annulée'];

        // Créer 35 bons de commande clients avec des états variés
        for ($i = 1; $i <= 35; $i++) {
            $client = $clients->random();
            $magasin = $magasins->random();
            $utilisateur = $utilisateurs->random();
            
            // Distribution des états : 25% Créée, 40% Validée, 30% Expédiée, 5% Annulée
            $rand = rand(1, 100);
            if ($rand <= 25) {
                $etat = 1; // Créée
            } elseif ($rand <= 65) {
                $etat = 5; // Validée
            } elseif ($rand <= 95) {
                $etat = 11; // Expédiée
            } else {
                $etat = 0; // Annulée
            }
            
            $bcc = BonCommandeClient::create([
                'date_' => $startDate->clone()->addDays(rand(0, 180))->format('Y-m-d'),
                'id_client' => $client->id_client,
                'id_magasin' => $magasin->id_magasin,
                'description' => null,
                'id_proforma_client' => null,
                'etat' => $etat,
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
            $etatLabel = $etats[$etat] ?? 'Inconnue';
            $this->command->line("  ✓ Bon créé: {$bcc->id_bon_commande_client} pour {$client->nom} - État: {$etatLabel}");
        }

        $this->command->info("✅ {$bccCount} bons de commande clients créés avec succès!");
    }
}
