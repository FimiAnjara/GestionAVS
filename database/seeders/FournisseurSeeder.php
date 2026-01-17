<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fournisseur::create([
            'id_fournisseur' => 'FOUR-' . time() . '001',
            'nom' => 'ChimiFrance',
            'lieux' => 'Lyon',
        ]);

        Fournisseur::create([
            'id_fournisseur' => 'FOUR-' . time() . '002',
            'nom' => 'EuroClean Distributeur',
            'lieux' => 'Marseille',
        ]);

        Fournisseur::create([
            'id_fournisseur' => 'FOUR-' . time() . '003',
            'nom' => 'ProduitsHygiene.com',
            'lieux' => 'Paris',
        ]);
    }
}
