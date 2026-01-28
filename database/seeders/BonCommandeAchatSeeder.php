<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\BonCommande;
use App\Models\BonCommandeFille;
use App\Models\Fournisseur;
use App\Models\Magasin;
use App\Models\ProformaFournisseur;
use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class BonCommandeAchatSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🛒 Création des données d\'achats (Bons de Commande)...');

        $fournisseurs = Fournisseur::all();
        $articles = Article::all();
        $magasins = Magasin::all();
        $utilisateurs = Utilisateur::all();

        if ($fournisseurs->isEmpty() || $articles->isEmpty() || $magasins->isEmpty()) {
            $this->command->error('❌ Erreur: Assurez-vous que les fournisseurs, articles et magasins existent');
            return;
        }

        $startDate = now()->subMonths(6);
        $bcCount = 0;

        // États possibles : 1=Créée, 5=Validée, 11=Reçue, 0=Annulée
        $etats = [1 => 'Créée', 5 => 'Validée', 11 => 'Reçue', 0 => 'Annulée'];
        
        // Créer 30 bons de commande d'achat avec des états variés
        for ($i = 1; $i <= 30; $i++) {
            $fournisseur = $fournisseurs->random();
            $magasin = $magasins->random();
            $utilisateur = $utilisateurs->random();
            
            // Créer une proforma pour ce bon
            $pf = ProformaFournisseur::create([
                'id_proformaFournisseur' => 'PF_' . strtoupper(uniqid()),
                'date_' => $startDate->clone()->addDays(rand(0, 180))->format('Y-m-d'),
                'id_fournisseur' => $fournisseur->id_fournisseur,
                'etat' => 1,
            ]);
            
            // Distribution des états : 30% Créée, 35% Validée, 30% Reçue, 5% Annulée
            $rand = rand(1, 100);
            if ($rand <= 30) {
                $etat = 1; // Créée
            } elseif ($rand <= 65) {
                $etat = 5; // Validée
            } elseif ($rand <= 95) {
                $etat = 11; // Reçue
            } else {
                $etat = 0; // Annulée
            }

            $bc = BonCommande::create([
                'id_bonCommande' => 'BC_' . strtoupper(uniqid()),
                'date_' => $pf->date_->format('Y-m-d'),
                'id_magasin' => $magasin->id_magasin,
                'id_proformaFournisseur' => $pf->id_proformaFournisseur,
                'id_factureFournisseur' => null,
                'etat' => $etat,
                'id_utilisateur' => $utilisateur->id_utilisateur,
            ]);

            // Ajouter 2-5 articles par bon de commande
            $nbArticles = rand(2, 5);
            $articlesSelectionnés = $articles->random($nbArticles);

            foreach ($articlesSelectionnés as $article) {
                $quantite = rand(5, 50);
                $prix = $article->articleFille?->first()?->prix ?? rand(5000, 100000);
                
                BonCommandeFille::create([
                    'id_bonCommandeFille' => 'BCF_' . strtoupper(uniqid()),
                    'id_article' => $article->id_article,
                    'quantite' => $quantite,
                    'prix_achat' => $prix,
                    'id_bonCommande' => $bc->id_bonCommande,
                ]);
            }

            $bcCount++;
            $etatLabel = $etats[$etat] ?? 'Inconnue';
            $this->command->line("  ✓ Bon créé: {$bc->id_bonCommande} pour {$fournisseur->nom} - État: {$etatLabel}");
        }

        $this->command->info("✅ {$bcCount} bons de commande d'achat créés avec succès!");
    }
}
