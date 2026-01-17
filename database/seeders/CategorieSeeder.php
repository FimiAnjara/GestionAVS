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
        Categorie::create([
            'id_categorie' => 'CAT-' . time() . '001',
            'libelle' => 'Biscuits & Confiserie',
        ]);

        Categorie::create([
            'id_categorie' => 'CAT-' . time() . '002',
            'libelle' => 'Huiles & Condiments',
        ]);

        Categorie::create([
            'id_categorie' => 'CAT-' . time() . '003',
            'libelle' => 'Sucres & Produits Secs',
        ]);
    }
}
