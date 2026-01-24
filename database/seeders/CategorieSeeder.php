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
            ['id' => 'CAT_PPN', 'libelle' => 'Produits de Première Nécessité'],
            ['id' => 'CAT_ALIM', 'libelle' => 'Alimentation & Boissons'],
            ['id' => 'CAT_AGRI', 'libelle' => 'Agriculture & Semences'],
            ['id' => 'CAT_VET', 'libelle' => 'Vétérinaire & Animaux'],
            ['id' => 'CAT_OUTIL', 'libelle' => 'Outillage & Matériel'],
            ['id' => 'CAT_TECH', 'libelle' => 'Informatique & High-Tech'],
            ['id' => 'CAT_TEL', 'libelle' => 'Téléphonie & Mobilité'],
        ];

        foreach ($categories as $cat) {
            Categorie::updateOrCreate(
                ['id_categorie' => $cat['id']],
                ['libelle' => $cat['libelle']]
            );
        }
    }
}
