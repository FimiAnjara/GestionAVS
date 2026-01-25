<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sites = [
            [
                'id_site' => 'site_001',
                'libelle' => 'Site Principal',
                'id_entite' => 'ent_001',
            ],
        ];

        foreach ($sites as $site) {
            DB::table('site')->updateOrInsert(
                ['id_site' => $site['id_site']],
                $site
            );
        }
    }
}
