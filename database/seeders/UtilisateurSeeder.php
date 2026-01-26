<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $utilisateurs = [
            // === AGRIVET (Agriculture et Vétérinaire) ===
            [
                'id_utilisateur' => 'user_001',
                'email' => 'acheteur@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_001',
                'id_role' => 'role_001',
                'id_entite' => 'ENT_AGRIVET',
                'id_site' => 'SITE_AGR_01',
                'id_magasin' => 'MAG_AGR_S1_M1',
            ],
            [
                'id_utilisateur' => 'user_002',
                'email' => 'magasinier@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_002',
                'id_role' => 'role_002',
                'id_entite' => 'ENT_AGRIVET',
                'id_site' => 'SITE_AGR_01',
                'id_magasin' => 'MAG_AGR_S1_M2',
            ],
            [
                'id_utilisateur' => 'user_003',
                'email' => 'chef.magasin@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_002',
                'id_role' => 'role_003',
                'id_entite' => 'ENT_AGRIVET',
                'id_site' => 'SITE_AGR_02',
                'id_magasin' => 'MAG_AGR_S2_M1',
            ],
            [
                'id_utilisateur' => 'user_004',
                'email' => 'commercial@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_003',
                'id_role' => 'role_004',
                'id_entite' => 'ENT_AGRIVET',
                'id_site' => 'SITE_AGR_03',
                'id_magasin' => 'MAG_AGR_S3_M1',
            ],
            // === NEXTSOLUTION (Solutions Technologiques) ===
            [
                'id_utilisateur' => 'user_005',
                'email' => 'caissier@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_003',
                'id_role' => 'role_005',
                'id_entite' => 'ENT_NEXTSOL',
                'id_site' => 'SITE_NEX_01',
                'id_magasin' => 'MAG_NEX_S1_M1',
            ],
            [
                'id_utilisateur' => 'user_006',
                'email' => 'service.client@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_003',
                'id_role' => 'role_006',
                'id_entite' => 'ENT_NEXTSOL',
                'id_site' => 'SITE_NEX_01',
                'id_magasin' => 'MAG_NEX_S1_M2',
            ],
            [
                'id_utilisateur' => 'user_007',
                'email' => 'comptable@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_004',
                'id_role' => 'role_007',
                'id_entite' => 'ENT_NEXTSOL',
                'id_site' => 'SITE_NEX_02',
                'id_magasin' => null,
            ],
            [
                'id_utilisateur' => 'user_008',
                'email' => 'tresorier@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_004',
                'id_role' => 'role_008',
                'id_entite' => 'ENT_NEXTSOL',
                'id_site' => 'SITE_NEX_03',
                'id_magasin' => null,
            ],
            // === DAILYNEED (Produits du quotidien) ===
            [
                'id_utilisateur' => 'user_009',
                'email' => 'controleur@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_004',
                'id_role' => 'role_009',
                'id_entite' => 'ENT_DAILYN',
                'id_site' => 'SITE_DAI_01',
                'id_magasin' => null,
            ],
            [
                'id_utilisateur' => 'user_010',
                'email' => 'daf@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_004',
                'id_role' => 'role_010',
                'id_entite' => 'ENT_DAILYN',
                'id_site' => 'SITE_DAI_01',
                'id_magasin' => null,
            ],
            [
                'id_utilisateur' => 'user_011',
                'email' => 'responsable@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_001',
                'id_role' => 'role_011',
                'id_entite' => 'ENT_DAILYN',
                'id_site' => 'SITE_DAI_02',
                'id_magasin' => 'MAG_DAI_S2_M1',
            ],
            [
                'id_utilisateur' => 'user_012',
                'email' => 'responsable.stock@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_002',
                'id_role' => 'role_011',
                'id_entite' => 'ENT_DAILYN',
                'id_site' => 'SITE_DAI_03',
                'id_magasin' => 'MAG_DAI_S3_M1',
            ],
            [
                'id_utilisateur' => 'user_013',
                'email' => 'responsable.vente@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_003',
                'id_role' => 'role_011',
                'id_entite' => 'ENT_AGRIVET',
                'id_site' => 'SITE_AGR_04',
                'id_magasin' => 'MAG_AGR_S4_M1',
            ],
            [
                'id_utilisateur' => 'user_014',
                'email' => 'responsable.finance@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_004',
                'id_role' => 'role_011',
                'id_entite' => 'ENT_NEXTSOL',
                'id_site' => 'SITE_NEX_04',
                'id_magasin' => null,
            ],
            // === DIRECTEUR GÉNÉRAL (accès global) ===
            [
                'id_utilisateur' => 'user_directeur',
                'email' => 'directeur@example.com',
                'mdp' => Hash::make('password123'),
                'id_departement' => 'dept_005',
                'id_role' => 'role_012',
                'id_entite' => null, // Peut voir toutes les entités
                'id_site' => null,   // Peut voir tous les sites
                'id_magasin' => null, // Peut voir tous les magasins
            ],
            // === ADMIN ===
            [
                'id_utilisateur' => 'user_admin',
                'email' => 'admin@example.com',
                'mdp' => Hash::make('admin123'),
                'id_departement' => 'dept_006',
                'id_role' => 'role_012',
                'id_entite' => null,
                'id_site' => null,
                'id_magasin' => null,
            ],
        ];

        foreach ($utilisateurs as $utilisateur) {
            Utilisateur::updateOrCreate(
                ['id_utilisateur' => $utilisateur['id_utilisateur']],
                $utilisateur
            );
        }
    }
}
