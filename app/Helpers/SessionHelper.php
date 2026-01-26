<?php

namespace App\Helpers;

/**
 * Helper pour récupérer les informations de session de l'utilisateur connecté
 * Utile pour la traçabilité des opérations (savoir quel utilisateur et quel magasin)
 */
class SessionHelper
{
    /**
     * Récupérer l'ID de l'utilisateur connecté
     */
    public static function getUserId(): ?string
    {
        return session('user_id');
    }

    /**
     * Récupérer l'email de l'utilisateur connecté
     */
    public static function getUserEmail(): ?string
    {
        return session('user_email');
    }

    /**
     * Récupérer le rôle de l'utilisateur connecté
     */
    public static function getUserRole(): ?string
    {
        return session('user_role');
    }

    /**
     * Récupérer l'ID du magasin de l'utilisateur connecté
     */
    public static function getMagasinId(): ?string
    {
        return session('user_magasin_id');
    }

    /**
     * Récupérer le nom du magasin de l'utilisateur connecté
     */
    public static function getMagasinNom(): ?string
    {
        return session('user_magasin_nom');
    }

    /**
     * Récupérer l'ID du site de l'utilisateur connecté
     */
    public static function getSiteId(): ?string
    {
        return session('user_site_id');
    }

    /**
     * Récupérer le nom du site de l'utilisateur connecté
     */
    public static function getSiteNom(): ?string
    {
        return session('user_site_nom');
    }

    /**
     * Récupérer l'ID de l'entité de l'utilisateur connecté
     */
    public static function getEntiteId(): ?string
    {
        return session('user_entite_id');
    }

    /**
     * Récupérer le nom de l'entité de l'utilisateur connecté
     */
    public static function getEntiteNom(): ?string
    {
        return session('user_entite_nom');
    }

    /**
     * Récupérer toutes les informations de session de l'utilisateur
     */
    public static function getAll(): array
    {
        return [
            'user_id' => self::getUserId(),
            'user_email' => self::getUserEmail(),
            'user_role' => self::getUserRole(),
            'user_departement' => session('user_departement'),
            'magasin_id' => self::getMagasinId(),
            'magasin_nom' => self::getMagasinNom(),
            'site_id' => self::getSiteId(),
            'site_nom' => self::getSiteNom(),
            'entite_id' => self::getEntiteId(),
            'entite_nom' => self::getEntiteNom(),
        ];
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public static function isLoggedIn(): bool
    {
        return session()->has('user_id') && session('user_id') !== null;
    }

    /**
     * Vérifier si l'utilisateur a un magasin assigné
     */
    public static function hasMagasin(): bool
    {
        return session()->has('user_magasin_id') && session('user_magasin_id') !== null;
    }

    /**
     * Récupérer les données à ajouter automatiquement lors de la création d'un enregistrement
     * (pour la traçabilité)
     */
    public static function getTraceData(): array
    {
        return [
            'id_utilisateur' => self::getUserId(),
            'id_magasin' => self::getMagasinId(),
        ];
    }
}
