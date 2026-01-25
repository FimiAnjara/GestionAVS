<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeMvtStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TypeMvtStock::updateOrCreate(
            ['id_type_mvt' => 'E'],
            ['libelle' => 'Entrée', 'description' => 'Mouvement augmentant le stock (Achat, Retour client, Ajustement positif)']
        );

        \App\Models\TypeMvtStock::updateOrCreate(
            ['id_type_mvt' => 'S'],
            ['libelle' => 'Sortie', 'description' => 'Mouvement diminuant le stock (Vente, Perte, Ajustement négatif)']
        );
    }
}
