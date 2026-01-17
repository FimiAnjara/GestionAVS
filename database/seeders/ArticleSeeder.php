<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Unite;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les catégories et unités
        $categories = Categorie::all();
        $unites = Unite::all();

        $articles = [
            // Biscuits & Confiserie
            ['nom' => 'Biscuit Chocolat Premium 500g', 'stock' => 150, 'categorie' => 0, 'unite' => 2],
            ['nom' => 'Bonbons Assortis 1kg', 'stock' => 100, 'categorie' => 0, 'unite' => 2],
            ['nom' => 'Biscuit Sablé Beurre 250g', 'stock' => 120, 'categorie' => 0, 'unite' => 2],
            ['nom' => 'Gâteau Éponge Vanille', 'stock' => 80, 'categorie' => 0, 'unite' => 3],
            ['nom' => 'Wafer Noisette 200g', 'stock' => 90, 'categorie' => 0, 'unite' => 2],

            // Huiles & Condiments
            ['nom' => 'Huile d\'Olive Vierge 1L', 'stock' => 200, 'categorie' => 1, 'unite' => 0],
            ['nom' => 'Huile de Tournesol 5L', 'stock' => 75, 'categorie' => 1, 'unite' => 0],
            ['nom' => 'Vinaigre Blanc 750ml', 'stock' => 110, 'categorie' => 1, 'unite' => 0],
            ['nom' => 'Sel Fin Raffiné 1kg', 'stock' => 250, 'categorie' => 1, 'unite' => 2],
            ['nom' => 'Sauce Tomate 400g', 'stock' => 160, 'categorie' => 1, 'unite' => 3],

            // Sucres & Produits Secs
            ['nom' => 'Sucre Blanc Cristallisé 1kg', 'stock' => 300, 'categorie' => 2, 'unite' => 2],
            ['nom' => 'Sucre Roux Cassonade 500g', 'stock' => 180, 'categorie' => 2, 'unite' => 2],
            ['nom' => 'Farine Blanche Premium 1kg', 'stock' => 220, 'categorie' => 2, 'unite' => 2],
            ['nom' => 'Riz Blanc Long Grain 2kg', 'stock' => 140, 'categorie' => 2, 'unite' => 2],
            ['nom' => 'Pâtes Alimentaires 500g', 'stock' => 280, 'categorie' => 2, 'unite' => 3],
        ];

        foreach ($articles as $index => $article) {
            Article::create([
                'id_article' => 'ART-' . time() . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nom' => $article['nom'],
                'stock' => $article['stock'],
                'id_categorie' => $categories[$article['categorie']]->id_categorie,
                'id_unite' => $unites[$article['unite']]->id_unite,
                'photo' => null,
            ]);
        }
    }
}
