<?php

namespace App\Helpers;

class PermissionHelper
{
    /**
     * Définition des permissions par rôle
     */
    public static function getPermissions(): array
    {
        return [
            // Acheteur - Département Achat
            'Acheteur' => [
                'modules' => ['achats'],
                'menus' => [
                    'proforma-fournisseur' => ['create', 'list'],
                    'bon-commande' => ['create', 'list'],
                    'fournisseurs' => ['list'],
                    'articles' => ['list'],
                ],
            ],
            
            // Magasinier - Département Stock
            'Magasinier' => [
                'modules' => ['stock'],
                'menus' => [
                    'bon-reception' => ['create', 'list'],
                    'mvt-stock' => ['create', 'list', 'details', 'etat'],
                    'articles' => ['list'],
                    'magasin' => ['list'],
                ],
            ],
            
            // Chef Magasin - Département Stock
            'Chef Magasin' => [
                'modules' => ['stock', 'achats'],
                'menus' => [
                    'bon-reception' => ['create', 'list'],
                    'mvt-stock' => ['create', 'list', 'details', 'etat'],
                    'articles' => ['create', 'list'],
                    'categories' => ['create', 'list'],
                    'unites' => ['create', 'list'],
                    'magasin' => ['create', 'list', 'carte'],
                    'bon-commande' => ['list'],
                ],
            ],
            
            // Commercial - Département Vente
            'Commercial' => [
                'modules' => ['vente', 'tiers'],
                'menus' => [
                    'clients' => ['create', 'list'],
                    'articles' => ['list'],
                    'mvt-stock' => ['etat'],
                    'proforma' => ['create', 'list'],
                    'proforma-client' => ['create', 'list'],
                    'commande' => ['create', 'list'],
                    'bon-commande-client' => ['create', 'list'],
                    'facture-client' => ['list'],
                    'bon-livraison' => ['create', 'list'],
                ],
            ],
            
            // Caissier / Facturation - Département Vente
            'Caissier / Facturation' => [
                'modules' => ['vente', 'finance'],
                'menus' => [
                    'clients' => ['list'],
                    'caisse' => ['list'],
                    'mvt-caisse' => ['create', 'list', 'etat'],
                    'proforma' => ['list'],
                    'proforma-client' => ['list'],
                    'commande' => ['list'],
                    'bon-commande-client' => ['list'],
                    'facture-client' => ['create', 'list'],
                    'bon-livraison' => ['list'],
                ],
            ],
            
            // Service Client - Département Vente
            'Service Client' => [
                'modules' => ['vente', 'tiers'],
                'menus' => [
                    'clients' => ['create', 'list'],
                    'articles' => ['list'],
                    'mvt-stock' => ['etat'],
                    'proforma' => ['create', 'list'],
                    'proforma-client' => ['create', 'list'],
                    'commande' => ['create', 'list'],
                    'bon-commande-client' => ['create', 'list'],
                    'facture-client' => ['list'],
                    'bon-livraison' => ['create', 'list'],
                ],
            ],
            
            // Comptable - Département Finance
            'Comptable' => [
                'modules' => ['finance', 'achats'],
                'menus' => [
                    'facture-fournisseur' => ['create', 'list'],
                    'caisse' => ['list'],
                    'mvt-caisse' => ['list', 'etat'],
                    'bon-commande' => ['list'],
                    'bon-reception' => ['list'],
                ],
            ],
            
            // Trésorier - Département Finance
            'Trésorier' => [
                'modules' => ['finance'],
                'menus' => [
                    'caisse' => ['create', 'list'],
                    'mvt-caisse' => ['create', 'list', 'etat'],
                ],
            ],
            
            // Contrôleur - Département Finance
            'Contrôleur' => [
                'modules' => ['finance', 'stock', 'achats', 'vente'],
                'menus' => [
                    'facture-fournisseur' => ['list'],
                    'bon-commande' => ['list'],
                    'bon-reception' => ['list'],
                    'mvt-stock' => ['list', 'details', 'etat'],
                    'caisse' => ['list'],
                    'mvt-caisse' => ['list', 'etat'],
                ],
            ],
            
            // DAF - Département Finance
            'DAF' => [
                'modules' => ['finance', 'stock', 'achats', 'vente', 'tiers'],
                'menus' => [
                    'facture-fournisseur' => ['create', 'list'],
                    'bon-commande' => ['list'],
                    'bon-reception' => ['list'],
                    'mvt-stock' => ['list', 'details', 'etat'],
                    'caisse' => ['create', 'list'],
                    'mvt-caisse' => ['create', 'list', 'etat'],
                    'clients' => ['list'],
                    'fournisseurs' => ['list'],
                ],
            ],
            
            // Responsable - Tous départements
            'Responsable' => [
                'modules' => ['achats', 'stock', 'vente', 'finance', 'tiers', 'produits', 'magasin'],
                'menus' => [
                    'proforma-fournisseur' => ['create', 'list'],
                    'bon-commande' => ['create', 'list'],
                    'facture-fournisseur' => ['create', 'list'],
                    'bon-reception' => ['create', 'list'],
                    'proforma' => ['create', 'list'],
                    'proforma-client' => ['create', 'list'],
                    'commande' => ['create', 'list'],
                    'bon-commande-client' => ['create', 'list'],
                    'facture-client' => ['create', 'list'],
                    'bon-livraison' => ['create', 'list'],
                    'clients' => ['create', 'list'],
                    'fournisseurs' => ['create', 'list'],
                    'articles' => ['create', 'list'],
                    'mvt-stock' => ['create', 'list', 'details', 'etat'],
                    'categories' => ['create', 'list'],
                    'unites' => ['create', 'list'],
                    'magasin' => ['create', 'list', 'carte'],
                    'caisse' => ['create', 'list'],
                    'mvt-caisse' => ['create', 'list', 'etat'],
                ],
            ],
            
            // Directeur Général - Accès total
            'Directeur Général' => [
                'modules' => ['all'],
                'menus' => [
                    'dashboard' => ['global'],
                    'proforma-fournisseur' => ['create', 'list'],
                    'bon-commande' => ['create', 'list'],
                    'facture-fournisseur' => ['create', 'list'],
                    'bon-reception' => ['create', 'list'],
                    'proforma' => ['create', 'list'],
                    'proforma-client' => ['create', 'list'],
                    'commande' => ['create', 'list'],
                    'bon-commande-client' => ['create', 'list'],
                    'facture-client' => ['create', 'list'],
                    'bon-livraison' => ['create', 'list'],
                    'clients' => ['create', 'list'],
                    'fournisseurs' => ['create', 'list'],
                    'articles' => ['create', 'list'],
                    'mvt-stock' => ['create', 'list', 'details', 'etat'],
                    'categories' => ['create', 'list'],
                    'unites' => ['create', 'list'],
                    'magasin' => ['create', 'list', 'carte'],
                    'caisse' => ['create', 'list'],
                    'mvt-caisse' => ['create', 'list', 'etat'],
                ],
            ],
        ];
    }

    /**
     * Vérifier si un rôle a accès à un menu
     */
    public static function hasMenuAccess(?string $role, string $menu, ?string $action = null): bool
    {
        if (!$role) return false;
        
        $permissions = self::getPermissions();
        
        if (!isset($permissions[$role])) return false;
        
        $rolePerms = $permissions[$role];
        
        if (!isset($rolePerms['menus'][$menu])) return false;
        
        if ($action === null) return true;
        
        return in_array($action, $rolePerms['menus'][$menu]);
    }

    /**
     * Vérifier si un rôle a accès à un module
     */
    public static function hasModuleAccess(?string $role, string $module): bool
    {
        if (!$role) return false;
        
        $permissions = self::getPermissions();
        
        if (!isset($permissions[$role])) return false;
        
        $modules = $permissions[$role]['modules'];
        
        return in_array('all', $modules) || in_array($module, $modules);
    }

    /**
     * Récupérer tous les menus accessibles pour un rôle
     */
    public static function getAccessibleMenus(?string $role): array
    {
        if (!$role) return [];
        
        $permissions = self::getPermissions();
        
        if (!isset($permissions[$role])) return [];
        
        return $permissions[$role]['menus'] ?? [];
    }
}
