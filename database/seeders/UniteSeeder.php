<?php

namespace Database\Seeders;

use App\Models\Unite;
use Illuminate\Database\Seeder;

class UniteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unites = [
            ['id' => 'UNI_L', 'libelle' => 'Litre (L)'],
            ['id' => 'UNI_KG', 'libelle' => 'Kilogramme (kg)'],
            ['id' => 'UNI_PCE', 'libelle' => 'Unité (pce)'],
            ['id' => 'UNI_SAC', 'libelle' => 'Sac'],
            ['id' => 'UNI_CARTON', 'libelle' => 'Carton'],
            ['id' => 'UNI_PK', 'libelle' => 'Paquet'],
        ];

        foreach ($unites as $unite) {
            Unite::updateOrCreate(
                ['id_unite' => $unite['id']],
                ['libelle' => $unite['libelle']]
            );
        }
    }
}
