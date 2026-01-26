<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\ArticleFille;
use Carbon\Carbon;

class ArticleFillePrixSeeder extends Seeder
{
    /**
     * Ajouter des prix de vente aux articles via ArticleFille
     */
    public function run(): void
    {
        // Récupérer tous les articles
        $articles = Article::all();

        // Prix de vente par défaut selon les catégories (exemples)
        $prixParCategorie = [
            // Catégories courantes
            'Électronique' => [50000, 500000],
            'Informatique' => [100000, 2000000],
            'Fournitures' => [1000, 50000],
            'Bureautique' => [5000, 100000],
            'Alimentation' => [500, 20000],
            'Boissons' => [1000, 15000],
            'Textile' => [10000, 150000],
            'Outillage' => [5000, 200000],
            'Construction' => [10000, 500000],
            'Agricole' => [5000, 300000],
            'Pièces détachées' => [2000, 100000],
            'default' => [5000, 100000],
        ];

        foreach ($articles as $article) {
            // Vérifier si l'article a déjà un ArticleFille avec prix
            $existingFille = ArticleFille::where('id_article', $article->id_article)->first();
            
            if ($existingFille) {
                // Mettre à jour si le prix est 0 ou null
                if (!$existingFille->prix || $existingFille->prix == 0) {
                    $prix = $this->genererPrix($article, $prixParCategorie);
                    $existingFille->update([
                        'prix' => $prix,
                        'date_' => Carbon::now(),
                    ]);
                    $this->command->info("Prix mis à jour pour {$article->nom}: " . number_format($prix, 0, ',', ' ') . " Ar");
                }
            } else {
                // Créer un nouveau ArticleFille avec prix
                $prix = $this->genererPrix($article, $prixParCategorie);
                
                ArticleFille::create([
                    'id_articleFille' => 'AF-' . $article->id_article . '-' . time() . rand(100, 999),
                    'id_article' => $article->id_article,
                    'prix' => $prix,
                    'date_' => Carbon::now(),
                    'quantite' => 0,
                    'id_unite' => $article->id_unite,
                ]);
                
                $this->command->info("Prix créé pour {$article->nom}: " . number_format($prix, 0, ',', ' ') . " Ar");
            }
        }

        $this->command->info("\n✅ Prix de vente ajoutés pour " . $articles->count() . " articles.");
    }

    /**
     * Générer un prix réaliste basé sur la catégorie
     */
    private function genererPrix(Article $article, array $prixParCategorie): float
    {
        $categorie = $article->categorie?->libelle ?? 'default';
        
        // Trouver la plage de prix pour cette catégorie
        $plage = $prixParCategorie[$categorie] ?? $prixParCategorie['default'];
        
        // Générer un prix aléatoire dans la plage
        $min = $plage[0];
        $max = $plage[1];
        
        // Arrondir au millier près pour des prix réalistes
        $prix = rand($min, $max);
        $prix = round($prix / 1000) * 1000;
        
        // S'assurer que le prix minimum est 1000 Ar
        return max($prix, 1000);
    }
}
