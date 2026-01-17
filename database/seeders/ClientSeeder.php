<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'id_client' => 'CLI-' . time() . '001',
            'nom' => 'SARL Hygiène Plus',
        ]);

        Client::create([
            'id_client' => 'CLI-' . time() . '002',
            'nom' => 'Entreprise de Nettoyage Pro',
        ]);

        Client::create([
            'id_client' => 'CLI-' . time() . '003',
            'nom' => 'Hôtel du Centre Ville',
        ]);
    }
}
