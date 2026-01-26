<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Charger tous les seeders dans le bon ordre
        $this->call([
            // 1. Tables de référence (sans dépendances)
            RoleSeeder::class,
            DepartementSeeder::class,
            CategorieSeeder::class,
            UniteSeeder::class,
            TypeEvaluationStockSeeder::class,
            TypeMvtStockSeeder::class,
            
            // 2. Organigramme (Groupe -> Entite -> Site -> Magasin)
            OrganigrammeSeeder::class,
            
            // 3. Utilisateurs (dépend de Role, Departement, Entite, Site, Magasin)
            UtilisateurSeeder::class,
            
            // 4. Données métier
            ClientSeeder::class,
            FournisseurSeeder::class,
            ArticleSeeder::class,
            CaisseSeeder::class,
        ]);
    }
}
