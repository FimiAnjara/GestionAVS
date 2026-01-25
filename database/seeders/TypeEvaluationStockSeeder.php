<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeEvaluationStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'id' => 'CMUP',
                'libelle' => 'CMUP',
                'description' => 'Coût Moyen Unitaire Pondéré - Évaluation basée sur le coût moyen pondéré des articles en stock.',
            ],
            [
                'id' => 'FIFO',
                'libelle' => 'FIFO',
                'description' => 'First-In, First-Out - Le premier article entré en stock est le premier sorti.',
            ],
            [
                'id' => 'LIFO',
                'libelle' => 'LIFO',
                'description' => 'Last-In, First-Out - Le dernier article entré en stock est le premier sorti.',
            ],
        ];

        foreach ($types as $type) {
            \App\Models\TypeEvaluationStock::updateOrCreate(
                ['id_type_evaluation_stock' => $type['id']],
                [
                    'libelle' => $type['libelle'],
                    'description' => $type['description'],
                ]
            );
        }
    }
}
