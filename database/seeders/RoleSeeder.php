<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id_role' => 'role_001', 'libelle' => 'Acheteur'],
            ['id_role' => 'role_002', 'libelle' => 'Magasinier'],
            ['id_role' => 'role_003', 'libelle' => 'Chef Magasin'],
            ['id_role' => 'role_004', 'libelle' => 'Commercial'],
            ['id_role' => 'role_005', 'libelle' => 'Caissier / Facturation'],
            ['id_role' => 'role_006', 'libelle' => 'Service Client'],
            ['id_role' => 'role_007', 'libelle' => 'Comptable'],
            ['id_role' => 'role_008', 'libelle' => 'Trésorier'],
            ['id_role' => 'role_009', 'libelle' => 'Contrôleur'],
            ['id_role' => 'role_010', 'libelle' => 'DAF'],
            ['id_role' => 'role_011', 'libelle' => 'Responsable'],
            ['id_role' => 'role_012', 'libelle' => 'Directeur Général'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['id_role' => $role['id_role']],
                ['libelle' => $role['libelle']]
            );
        }
    }
}
