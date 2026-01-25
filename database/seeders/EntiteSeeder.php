<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entites = [
            [
                'id_entite' => 'ent_001',
                'libelle' => 'Entreprise Principale',
                'id_groupe' => null,
            ],
        ];

        foreach ($entites as $entite) {
            DB::table('entite')->updateOrInsert(
                ['id_entite' => $entite['id_entite']],
                $entite
            );
        }
    }
}
