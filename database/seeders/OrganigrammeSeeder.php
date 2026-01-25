<?php

namespace Database\Seeders;

use App\Models\Groupe;
use App\Models\Entite;
use App\Models\Site;
use App\Models\Magasin;
use Illuminate\Database\Seeder;

class OrganigrammeSeeder extends Seeder
{
    /**
     * Coordonnées GPS des villes de Madagascar
     */
    private array $coordonneesVilles = [
        // Antananarivo et environs
        'Antananarivo' => ['lat' => -18.8792, 'lng' => 47.5079],
        'Tana Centre' => ['lat' => -18.9100, 'lng' => 47.5255],
        'Tana Nord' => ['lat' => -18.8500, 'lng' => 47.5200],
        'Tana Sud' => ['lat' => -18.9500, 'lng' => 47.5100],
        'Tana Est' => ['lat' => -18.9000, 'lng' => 47.5500],
        'Analakely' => ['lat' => -18.9137, 'lng' => 47.5256],
        'Behoririka' => ['lat' => -18.9050, 'lng' => 47.5280],
        'Andraharo' => ['lat' => -18.8850, 'lng' => 47.5350],
        'Ivandry' => ['lat' => -18.8800, 'lng' => 47.5400],
        
        // Autres grandes villes
        'Antsirabe' => ['lat' => -19.8659, 'lng' => 47.0333],
        'Fianarantsoa' => ['lat' => -21.4416, 'lng' => 47.0853],
        'Toamasina' => ['lat' => -18.1492, 'lng' => 49.4023],
        'Mahajanga' => ['lat' => -15.7167, 'lng' => 46.3167],
        'Toliara' => ['lat' => -23.3500, 'lng' => 43.6667],
        'Antsiranana' => ['lat' => -12.2795, 'lng' => 49.2913],
        'Nosy Be' => ['lat' => -13.3167, 'lng' => 48.2667],
        'Morondava' => ['lat' => -20.2833, 'lng' => 44.2833],
        'Ambositra' => ['lat' => -20.5167, 'lng' => 47.2500],
        'Manakara' => ['lat' => -22.1333, 'lng' => 48.0167],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 1 Groupe
        $groupe = Groupe::create([
            'id_groupe' => 'GRP_001',
            'nom' => 'Groupe Principal',
        ]);

        // Définir les entités avec leurs sites
        $entitesData = [
            [
                'id' => 'ENT_AGRIVET',
                'nom' => 'Agrivet',
                'description' => 'Agriculture et Vétérinaire',
                'code_couleur' => '#28a745',
                'sites' => ['Antananarivo', 'Antsirabe', 'Fianarantsoa', 'Toamasina'],
            ],
            [
                'id' => 'ENT_NEXTSOL',
                'nom' => 'NextSolution',
                'description' => 'Solutions Technologiques',
                'code_couleur' => '#007bff',
                'sites' => ['Mahajanga', 'Toliara', 'Antsiranana', 'Nosy Be'],
            ],
            [
                'id' => 'ENT_DAILYN',
                'nom' => 'DailyNeed',
                'description' => 'Produits du quotidien',
                'code_couleur' => '#fd7e14',
                'sites' => ['Analakely', 'Behoririka', 'Andraharo', 'Ivandry'],
            ],
        ];

        foreach ($entitesData as $entiteData) {
            // Créer l'entité
            $entite = Entite::create([
                'id_entite' => $entiteData['id'],
                'nom' => $entiteData['nom'],
                'description' => $entiteData['description'],
                'code_couleur' => $entiteData['code_couleur'],
                'logo' => null,
                'id_groupe' => $groupe->id_groupe,
            ]);

            // Créer les sites pour cette entité
            $siteIndex = 1;

            foreach ($entiteData['sites'] as $siteName) {
                $site = Site::create([
                    'id_site' => 'SITE_' . strtoupper(substr($entiteData['nom'], 0, 3)) . '_' . str_pad($siteIndex, 2, '0', STR_PAD_LEFT),
                    'localisation' => $siteName,
                    'id_entite' => $entite->id_entite,
                ]);

                // Récupérer les coordonnées de la ville
                $coords = $this->coordonneesVilles[$siteName] ?? ['lat' => -18.8792, 'lng' => 47.5079];

                // Créer 5 magasins pour ce site avec coordonnées réalistes
                for ($m = 1; $m <= 5; $m++) {
                    // Variation aléatoire autour du centre ville (environ 2-5 km)
                    $lat = $coords['lat'] + (rand(-50, 50) / 1000);
                    $lng = $coords['lng'] + (rand(-50, 50) / 1000);

                    Magasin::create([
                        'id_magasin' => 'MAG_' . strtoupper(substr($entiteData['nom'], 0, 3)) . '_S' . $siteIndex . '_M' . $m,
                        'nom' => 'Magasin ' . $siteName . ' #' . $m,
                        'latitude' => round($lat, 6),
                        'longitude' => round($lng, 6),
                        'id_site' => $site->id_site,
                    ]);
                }

                $siteIndex++;
            }
        }

        // $this->command->info('Organigramme créé avec succès!');
        // $this->command->info('- 1 Groupe');
        // $this->command->info('- 3 Entités (Agrivet, NextSolution, DailyNeed)');
        // $this->command->info('- 12 Sites (4 par entité)');
        // $this->command->info('- 60 Magasins (5 par site)');
        // $this->command->info('');
        // $this->command->info('Localisation des sites:');
        // $this->command->info('  Agrivet: Antananarivo, Antsirabe, Fianarantsoa, Toamasina');
        // $this->command->info('  NextSolution: Mahajanga, Toliara, Antsiranana, Nosy Be');
        // $this->command->info('  DailyNeed: Analakely, Behoririka, Andraharo, Ivandry');
    }
}
