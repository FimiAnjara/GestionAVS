<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Permissions par rôle
     * Format: 'role_id' => ['permission1', 'permission2']
     */
    private $permissions = [
        // Département Achats
        'demandeur_achat' => ['creer_da', 'voir_da'],
        'approbateur_n1' => ['valider_da', 'voir_da'],
        'approbateur_n2' => ['valider_da', 'voir_da'],
        'approbateur_n3' => ['valider_da', 'voir_da'],
        'acheteur' => ['creer_bc', 'voir_bc', 'gerer_fournisseurs'],
        'responsable_achats' => ['valider_bc', 'debloquer_litiges', 'voir_bc'],
        'finance_achats' => ['verifier_budget', 'voir_factures'],

        // Département Stock Logistique
        'magasinier_reception' => ['recevoir_fournisseur', 'voir_stock'],
        'magasinier_sortie' => ['livrer', 'sortir_stock', 'voir_stock'],
        'chef_magasin' => ['valider_transferts', 'voir_stock'],
        'responsable_stock' => ['inventaire', 'ajuster_stock', 'voir_stock'],
        'logisticien' => ['planifier_flux', 'voir_stock'],

        // Département Vente
        'commercial' => ['creer_devis', 'creer_commande', 'voir_clients'],
        'caissier' => ['creer_facture', 'encaissement', 'voir_factures'],
        'responsable_commercial' => ['valider_remises', 'voir_commandes'],
        'service_client' => ['gerer_retours', 'gerer_reclamations'],

        // Département Finance
        'comptable_fournisseur' => ['voir_factures_fournisseur', 'payer'],
        'comptable_client' => ['voir_factures_client', 'relancer'],
        'tresorier' => ['payer', 'encaisser'],
        'controleur_gestion' => ['voir_marges', 'voir_couts'],
        'daf' => ['valider_tous', 'voir_tous'],
    ];

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        // Récupérer l'ID du rôle depuis la relation
        $userRole = $user->role?->libelle;

        if (!$userRole) {
            return response()->json([
                'success' => false,
                'message' => 'Rôle utilisateur non trouvé',
            ], 403);
        }

        // Vérifier si le rôle correspond
        $requiredRoles = array_map('strtolower', explode('|', $role));
        $userRoleLower = strtolower(str_replace(' ', '_', $userRole));

        if (!in_array($userRoleLower, $requiredRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Rôle insuffisant.',
                'required_role' => $role,
                'user_role' => $userRole,
            ], 403);
        }

        return $next($request);
    }
}
