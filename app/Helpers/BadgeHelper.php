<?php

namespace App\Helpers;

class BadgeHelper
{
    private static $colors = [
        'Biscuits & Confiserie' => '#FF6B6B',      // Rouge
        'Huiles & Condiments' => '#4ECDC4',        // Turquoise
        'Sucres & Produits Secs' => '#FFD93D',     // Jaune
        'Fruits & Légumes' => '#6BCB77',           // Vert
        'Viandes & Poissons' => '#A8E6CF',         // Vert clair
        'Produits Laitiers' => '#FFB7B7',          // Rose
        'Boissons' => '#95E1D3',                   // Menthe
        'Snacks' => '#F38181',                     // Rose foncé
    ];

    private static $unitColors = [
        'L' => '#3498db',          // Bleu
        'kg' => '#2ecc71',         // Vert
        'Carton' => '#e74c3c',     // Rouge
        'pce' => '#f39c12',        // Orange
        'pk' => '#9b59b6',         // Violet
        'ml' => '#1abc9c',         // Turquoise
        'g' => '#34495e',          // Gris
        'Lot' => '#e67e22',        // Orange foncé
    ];

    public static function getCategoryColor($categoryName)
    {
        return self::$colors[$categoryName] ?? '#0056b3';
    }

    public static function getUnitColor($unitName)
    {
        return self::$unitColors[$unitName] ?? '#28a745';
    }

    public static function addCategoryColor($categoryName, $color)
    {
        self::$colors[$categoryName] = $color;
    }

    public static function addUnitColor($unitName, $color)
    {
        self::$unitColors[$unitName] = $color;
    }
}
