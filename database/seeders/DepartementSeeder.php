<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departements = [
            ['id_departement' => 'dept_001', 'libelle' => 'Achat'],
            ['id_departement' => 'dept_002', 'libelle' => 'Stock'],
            ['id_departement' => 'dept_003', 'libelle' => 'Vente'],
            ['id_departement' => 'dept_004', 'libelle' => 'Finance'],
            ['id_departement' => 'dept_005', 'libelle' => 'Direction'],
            ['id_departement' => 'dept_006', 'libelle' => 'Administration'],
        ];

        foreach ($departements as $departement) {
            Departement::updateOrCreate(
                ['id_departement' => $departement['id_departement']],
                $departement
            );
        }
    }
}
