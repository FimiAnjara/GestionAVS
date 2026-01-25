<?php

namespace Database\Seeders;

use App\Models\Caisse;
use Illuminate\Database\Seeder;

class CaisseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Caisse::updateOrCreate(
            ['id_caisse' => 'CAISSE_001'],
            ['libelle' => 'Airtel Money', 'montant' => 0]
        );

        Caisse::updateOrCreate(
            ['id_caisse' => 'CAISSE_002'],
            ['libelle' => 'MVola', 'montant' => 0]
        );

        Caisse::updateOrCreate(
            ['id_caisse' => 'CAISSE_003'],
            ['libelle' => 'Caisse Principale', 'montant' => 0]
        );
    }
}
