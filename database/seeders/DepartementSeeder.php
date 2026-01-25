<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departements = [
            ['id_departement' => 'dept_001', 'libelle' => 'Département Achats'],
            ['id_departement' => 'dept_002', 'libelle' => 'Département Stock Logistique'],
            ['id_departement' => 'dept_003', 'libelle' => 'Département Vente'],
            ['id_departement' => 'dept_004', 'libelle' => 'Département Finance'],
            ['id_departement' => 'dept_005', 'libelle' => 'Direction Générale']
        ];

        foreach ($departements as $departement) {
            Departement::updateOrCreate(
                ['id_departement' => $departement['id_departement']],
                ['libelle' => $departement['libelle']]
            );
        }
        
    }
}
