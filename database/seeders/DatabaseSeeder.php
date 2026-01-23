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
        // Charger tous les seeders
        $this->call([
            UtilisateurSeeder::class,
            CategorieSeeder::class,
            UniteSeeder::class,
            ClientSeeder::class,
            FournisseurSeeder::class,
            ArticleSeeder::class,
            CaisseSeeder::class,
            OrganigrammeSeeder::class,
        ]);
    }
}
