<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 'CAT_PPN', 'libelle' => 'Produits de Première Nécessité', 'est_perissable' => false],
            ['id' => 'CAT_ALIM', 'libelle' => 'Alimentation & Boissons', 'est_perissable' => true],
            ['id' => 'CAT_AGRI', 'libelle' => 'Agriculture & Semences', 'est_perissable' => false],
            ['id' => 'CAT_VET', 'libelle' => 'Vétérinaire & Animaux', 'est_perissable' => false],
            ['id' => 'CAT_OUTIL', 'libelle' => 'Outillage & Matériel', 'est_perissable' => false],
            ['id' => 'CAT_TECH', 'libelle' => 'Informatique & High-Tech', 'est_perissable' => false],
            ['id' => 'CAT_TEL', 'libelle' => 'Téléphonie & Mobilité', 'est_perissable' => false],
        ];

        foreach ($categories as $cat) {
            Categorie::updateOrCreate(
                ['id_categorie' => $cat['id']],
                ['libelle' => $cat['libelle'], 'est_perissable' => $cat['est_perissable']]
            );
        }
    }
}
