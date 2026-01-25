<?php

namespace Database\Seeders;

use App\Models\Magasin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MagasinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $magasins = [
            [
                'id_magasin' => 'MAG_001',
                'nom' => 'Magasin Centre Ville',
                'latitude' => -18.8792,
                'longitude' => 47.5079,
            ],
            [
                'id_magasin' => 'MAG_002',
                'nom' => 'Magasin Andohalo',
                'latitude' => -18.8756,
                'longitude' => 47.5147,
            ],
            [
                'id_magasin' => 'MAG_003',
                'nom' => 'Magasin Analakely',
                'latitude' => -18.8717,
                'longitude' => 47.5243,
            ],
            [
                'id_magasin' => 'MAG_004',
                'nom' => 'Magasin Isotry',
                'latitude' => -18.8956,
                'longitude' => 47.5312,
            ],
            [
                'id_magasin' => 'MAG_005',
                'nom' => 'Magasin Ankorondrano',
                'latitude' => -18.9011,
                'longitude' => 47.5089,
            ],
        ];

        foreach ($magasins as $magasin) {
            // Insérer sans location d'abord
            $id = $magasin['id_magasin'];
            $nom = $magasin['nom'];
            $lat = $magasin['latitude'];
            $lng = $magasin['longitude'];
            
            DB::statement(
                "INSERT INTO magasin (id_magasin, nom, location, created_at, updated_at) 
                 VALUES (?, ?, ST_GeomFromText(?, 4326), NOW(), NOW())",
                [$id, $nom, "POINT($lng $lat)"]
            );
        }
    }
}
