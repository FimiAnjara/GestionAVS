<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'fournisseur', 'Client', 'role', 'departement', 'categorie', 'unite',
            'caisse', 'emplacement', 'utilisateur', 'article', 'stock', 'proforma',
            'mvt_caisse', 'proformaFournisseur', 'proformaFille', 'articleFille',
            'mvt_stock', 'proformaFournisseurFille', 'lot', 'commande', 'livraison',
            'commandeFille', 'bonCommande', 'bonReception', 'bonCommandeFille',
            'transfert', 'bonReceptionFille', 'bon_livraison', 'bonLivraisonFille'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'deleted_at')) {
                        $table->softDeletes();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'fournisseur', 'Client', 'role', 'departement', 'categorie', 'unite',
            'caisse', 'emplacement', 'utilisateur', 'article', 'stock', 'proforma',
            'mvt_caisse', 'proformaFournisseur', 'proformaFille', 'articleFille',
            'mvt_stock', 'proformaFournisseurFille', 'lot', 'commande', 'livraison',
            'commandeFille', 'bonCommande', 'bonReception', 'bonCommandeFille',
            'transfert', 'bonReceptionFille', 'bon_livraison', 'bonLivraisonFille'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
