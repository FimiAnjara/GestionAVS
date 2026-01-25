<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute id_magasin et id_utilisateur aux tables pour la traçabilité
     */
    public function up(): void
    {
        // PROFORMA FOURNISSEUR (Demande d'achat)
        if (Schema::hasTable('proformaFournisseur')) {
            Schema::table('proformaFournisseur', function (Blueprint $table) {
                if (!Schema::hasColumn('proformaFournisseur', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_fournisseur');
                }
                if (!Schema::hasColumn('proformaFournisseur', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable()->after('id_magasin');
                }
            });
            
            // Ajouter les clés étrangères séparément
            try {
                Schema::table('proformaFournisseur', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign keys may already exist
            }
        }

        // BON DE COMMANDE
        if (Schema::hasTable('bonCommande')) {
            Schema::table('bonCommande', function (Blueprint $table) {
                if (!Schema::hasColumn('bonCommande', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_proformaFournisseur');
                }
            });
            
            try {
                Schema::table('bonCommande', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // FACTURE FOURNISSEUR
        if (Schema::hasTable('factureFournisseur')) {
            Schema::table('factureFournisseur', function (Blueprint $table) {
                if (!Schema::hasColumn('factureFournisseur', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_bonCommande');
                }
                if (!Schema::hasColumn('factureFournisseur', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable()->after('id_magasin');
                }
            });
            
            try {
                Schema::table('factureFournisseur', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // BON DE RECEPTION
        if (Schema::hasTable('bonReception')) {
            Schema::table('bonReception', function (Blueprint $table) {
                if (!Schema::hasColumn('bonReception', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable();
                }
            });
            
            try {
                Schema::table('bonReception', function (Blueprint $table) {
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // PROFORMA CLIENT
        if (Schema::hasTable('proforma')) {
            Schema::table('proforma', function (Blueprint $table) {
                if (!Schema::hasColumn('proforma', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_client');
                }
                if (!Schema::hasColumn('proforma', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable()->after('id_magasin');
                }
                if (!Schema::hasColumn('proforma', 'etat')) {
                    $table->integer('etat')->default(1)->after('validite');
                }
            });
            
            try {
                Schema::table('proforma', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // COMMANDE CLIENT
        if (Schema::hasTable('commande')) {
            Schema::table('commande', function (Blueprint $table) {
                if (!Schema::hasColumn('commande', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_client');
                }
            });
            
            try {
                Schema::table('commande', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // BON DE LIVRAISON
        if (Schema::hasTable('bon_livraison')) {
            Schema::table('bon_livraison', function (Blueprint $table) {
                if (!Schema::hasColumn('bon_livraison', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_bonCommande');
                }
                if (!Schema::hasColumn('bon_livraison', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable()->after('id_magasin');
                }
                if (!Schema::hasColumn('bon_livraison', 'etat')) {
                    $table->integer('etat')->default(1)->after('date_');
                }
            });
            
            try {
                Schema::table('bon_livraison', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // MVT CAISSE - ajouter id_magasin et id_utilisateur
        if (Schema::hasTable('mvt_caisse')) {
            Schema::table('mvt_caisse', function (Blueprint $table) {
                if (!Schema::hasColumn('mvt_caisse', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable()->after('id_caisse');
                }
                if (!Schema::hasColumn('mvt_caisse', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable()->after('id_magasin');
                }
                if (!Schema::hasColumn('mvt_caisse', 'etat')) {
                    $table->integer('etat')->default(1)->after('description');
                }
            });
            
            try {
                Schema::table('mvt_caisse', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // MVT STOCK - ajouter id_utilisateur (id_magasin existe déjà)
        if (Schema::hasTable('mvt_stock')) {
            Schema::table('mvt_stock', function (Blueprint $table) {
                if (!Schema::hasColumn('mvt_stock', 'id_utilisateur')) {
                    $table->string('id_utilisateur', 50)->nullable()->after('id_magasin');
                }
                if (!Schema::hasColumn('mvt_stock', 'etat')) {
                    $table->integer('etat')->default(1)->after('description');
                }
            });
            
            try {
                Schema::table('mvt_stock', function (Blueprint $table) {
                    $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateur')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // CAISSE - ajouter id_magasin
        if (Schema::hasTable('caisse')) {
            Schema::table('caisse', function (Blueprint $table) {
                if (!Schema::hasColumn('caisse', 'id_magasin')) {
                    $table->string('id_magasin', 50)->nullable();
                }
            });
            
            try {
                Schema::table('caisse', function (Blueprint $table) {
                    $table->foreign('id_magasin')->references('id_magasin')->on('magasin')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }

        // ARTICLE - ajouter id_magasin pour le stock par défaut
        if (Schema::hasTable('article')) {
            Schema::table('article', function (Blueprint $table) {
                if (!Schema::hasColumn('article', 'id_magasin_defaut')) {
                    $table->string('id_magasin_defaut', 50)->nullable();
                }
            });
            
            try {
                Schema::table('article', function (Blueprint $table) {
                    $table->foreign('id_magasin_defaut')->references('id_magasin')->on('magasin')->onDelete('set null');
                });
            } catch (\Exception $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'proformaFournisseur' => ['id_magasin', 'id_utilisateur'],
            'bonCommande' => ['id_magasin'],
            'factureFournisseur' => ['id_magasin', 'id_utilisateur'],
            'bonReception' => ['id_utilisateur'],
            'proforma' => ['id_magasin', 'id_utilisateur', 'etat'],
            'commande' => ['id_magasin'],
            'bon_livraison' => ['id_magasin', 'id_utilisateur', 'etat'],
            'mvt_caisse' => ['id_magasin', 'id_utilisateur', 'etat'],
            'mvt_stock' => ['id_utilisateur', 'etat'],
            'caisse' => ['id_magasin'],
            'article' => ['id_magasin_defaut'],
        ];

        foreach ($tables as $table => $columns) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) use ($columns, $table) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($table, $column)) {
                            try {
                                $t->dropForeign([$column]);
                            } catch (\Exception $e) {}
                            $t->dropColumn($column);
                        }
                    }
                });
            }
        }
    }
};
