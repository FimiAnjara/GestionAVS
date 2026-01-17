<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur par défaut
        $exists = DB::table('utilisateur')->where('id_utilisateur', 'UTIL-1')->exists();
        
        if (!$exists) {
            // D'abord créer le rôle s'il n'existe pas
            if (!DB::table('role')->where('id_role', 'ROLE-1')->exists()) {
                DB::table('role')->insert([
                    'id_role' => 'ROLE-1',
                    'libelle' => 'Administrateur',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Créer le département s'il n'existe pas
            if (!DB::table('departement')->where('id_departement', 'DEPT-1')->exists()) {
                DB::table('departement')->insert([
                    'id_departement' => 'DEPT-1',
                    'libelle' => 'Administration',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Créer l'utilisateur
            DB::table('utilisateur')->insert([
                'id_utilisateur' => 'UTIL-1',
                'email' => 'admin@grossiste.local',
                'mdp' => bcrypt('password'),
                'id_departement' => 'DEPT-1',
                'id_role' => 'ROLE-1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

