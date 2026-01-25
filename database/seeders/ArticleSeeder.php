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
        $articles = [
            // DailyNeed (ENT_DAILYN) - Produits de Première Nécessité
            ['id' => 'ART_RIZ', 'nom' => 'Riz Luxe 5kg', 'cat' => 'CAT_PPN', 'uni' => 'UNI_SAC', 'ent' => 'ENT_DAILYN', 'eval' => 'CMUP'],
            ['id' => 'ART_SUCRE', 'nom' => 'Sucre Blanc 1kg', 'cat' => 'CAT_PPN', 'uni' => 'UNI_KG', 'ent' => 'ENT_DAILYN', 'eval' => 'FIFO'],
            ['id' => 'ART_HUILE', 'nom' => 'Huile Végétale 1L', 'cat' => 'CAT_PPN', 'uni' => 'UNI_L', 'ent' => 'ENT_DAILYN', 'eval' => 'CMUP'],
            ['id' => 'ART_PATES', 'nom' => 'Pâtes alimentaires 500g', 'cat' => 'CAT_PPN', 'uni' => 'UNI_PK', 'ent' => 'ENT_DAILYN', 'eval' => 'LIFO'],
            ['id' => 'ART_BISCUIT', 'nom' => 'Biscuits Sablés', 'cat' => 'CAT_ALIM', 'uni' => 'UNI_PK', 'ent' => 'ENT_DAILYN', 'eval' => 'FIFO'],
            ['id' => 'ART_CAFE', 'nom' => 'Café moulu 250g', 'cat' => 'CAT_ALIM', 'uni' => 'UNI_PK', 'ent' => 'ENT_DAILYN', 'eval' => 'CMUP'],
            ['id' => 'ART_SAVON', 'nom' => 'Savon de ménage', 'cat' => 'CAT_PPN', 'uni' => 'UNI_PCE', 'ent' => 'ENT_DAILYN', 'eval' => 'FIFO'],
            ['id' => 'ART_LAIT', 'nom' => 'Lait en poudre 400g', 'cat' => 'CAT_ALIM', 'uni' => 'UNI_PCE', 'ent' => 'ENT_DAILYN', 'eval' => 'LIFO'],
            ['id' => 'ART_FARINE', 'nom' => 'Farine de blé 1kg', 'cat' => 'CAT_PPN', 'uni' => 'UNI_KG', 'ent' => 'ENT_DAILYN', 'eval' => 'CMUP'],
            ['id' => 'ART_SEL', 'nom' => 'Sel fin 500g', 'cat' => 'CAT_PPN', 'uni' => 'UNI_PK', 'ent' => 'ENT_DAILYN', 'eval' => 'FIFO'],

            // Agrivet (ENT_AGRIVET) - Agriculture et Vétérinaire
            ['id' => 'ART_ENGRAIS', 'nom' => 'Engrais NPK 50kg', 'cat' => 'CAT_AGRI', 'uni' => 'UNI_SAC', 'ent' => 'ENT_AGRIVET', 'eval' => 'CMUP'],
            ['id' => 'ART_MAIS', 'nom' => 'Semences de Maïs 1kg', 'cat' => 'CAT_AGRI', 'uni' => 'UNI_PK', 'ent' => 'ENT_AGRIVET', 'eval' => 'FIFO'],
            ['id' => 'ART_VOLAILLE', 'nom' => 'Nourriture pour volailles 25kg', 'cat' => 'CAT_VET', 'uni' => 'UNI_SAC', 'ent' => 'ENT_AGRIVET', 'eval' => 'LIFO'],
            ['id' => 'ART_VACCIN', 'nom' => 'Médicament Vétérinaire (Vaccin)', 'cat' => 'CAT_VET', 'uni' => 'UNI_PK', 'ent' => 'ENT_AGRIVET', 'eval' => 'FIFO'],
            ['id' => 'ART_PULV', 'nom' => 'Pulvérisateur manuel 16L', 'cat' => 'CAT_OUTIL', 'uni' => 'UNI_PCE', 'ent' => 'ENT_AGRIVET', 'eval' => 'CMUP'],
            ['id' => 'ART_PELLE', 'nom' => 'Pelle de terrassement', 'cat' => 'CAT_OUTIL', 'uni' => 'UNI_PCE', 'ent' => 'ENT_AGRIVET', 'eval' => 'CMUP'],
            ['id' => 'ART_RATEAU', 'nom' => 'Râteau de jardin', 'cat' => 'CAT_OUTIL', 'uni' => 'UNI_PCE', 'ent' => 'ENT_AGRIVET', 'eval' => 'CMUP'],
            ['id' => 'ART_BLOC', 'nom' => 'Bloc à lécher pour bétail', 'cat' => 'CAT_VET', 'uni' => 'UNI_KG', 'ent' => 'ENT_AGRIVET', 'eval' => 'FIFO'],
            ['id' => 'ART_HERB', 'nom' => 'Herbicide sélectif 1L', 'cat' => 'CAT_AGRI', 'uni' => 'UNI_L', 'ent' => 'ENT_AGRIVET', 'eval' => 'CMUP'],
            ['id' => 'ART_ABREV', 'nom' => 'Abreuvoir automatique', 'cat' => 'CAT_VET', 'uni' => 'UNI_PCE', 'ent' => 'ENT_AGRIVET', 'eval' => 'LIFO'],

            // NextSolution (ENT_NEXTSOL) - Solutions Technologiques
            ['id' => 'ART_LAPTOP', 'nom' => 'Ordinateur Portable i5', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'FIFO'],
            ['id' => 'ART_PHONE', 'nom' => 'Smartphone Android 128GB', 'cat' => 'CAT_TEL', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'FIFO'],
            ['id' => 'ART_PRINT', 'nom' => 'Imprimante Laser', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'FIFO'],
            ['id' => 'ART_HDD', 'nom' => 'Disque Dur Externe 1TB', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'CMUP'],
            ['id' => 'ART_ROUTER', 'nom' => 'Routeur Wi-Fi 6', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'CMUP'],
            ['id' => 'ART_KEYB', 'nom' => 'Clavier Mécanique Gamer', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'LIFO'],
            ['id' => 'ART_MOUSE', 'nom' => 'Souris Optique sans fil', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'LIFO'],
            ['id' => 'ART_HEADSET', 'nom' => 'Casque Audio Bluetooth', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'FIFO'],
            ['id' => 'ART_SCREEN', 'nom' => 'Écran 24 pouces Full HD', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'FIFO'],
            ['id' => 'ART_HDMI', 'nom' => 'Câble HDMI 2m', 'cat' => 'CAT_TECH', 'uni' => 'UNI_PCE', 'ent' => 'ENT_NEXTSOL', 'eval' => 'CMUP'],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['id_article' => $article['id']],
                [
                    'nom' => $article['nom'],
                    'id_categorie' => $article['cat'],
                    'id_unite' => $article['uni'],
                    'id_entite' => $article['ent'],
                    'id_type_evaluation_stock' => $article['eval'],
                    'photo' => null,
                ]
            );
        }
    }
}
