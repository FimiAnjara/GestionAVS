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
        Unite::create([
            'id_unite' => 'UNI-' . time() . '001',
            'libelle' => 'Litre (L)',
        ]);

        Unite::create([
            'id_unite' => 'UNI-' . time() . '002',
            'libelle' => 'Kilogramme (kg)',
        ]);

        Unite::create([
            'id_unite' => 'UNI-' . time() . '003',
            'libelle' => 'Carton',
        ]);

        Unite::create([
            'id_unite' => 'UNI-' . time() . '004',
            'libelle' => 'Unité (pce)',
        ]);

        Unite::create([
            'id_unite' => 'UNI-' . time() . '005',
            'libelle' => 'Paquet (pk)',
        ]);
    }
}
