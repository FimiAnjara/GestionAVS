<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;
    
    protected $table = 'article';
    protected $primaryKey = 'id_article';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_article', 'nom', 'id_unite', 'id_categorie', 'id_entite', 'id_type_evaluation_stock', 'photo'];

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite', 'id_unite');
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'id_categorie', 'id_categorie');
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class, 'id_entite', 'id_entite');
    }

    public function typeEvaluation()
    {
        return $this->belongsTo(TypeEvaluationStock::class, 'id_type_evaluation_stock', 'id_type_evaluation_stock');
    }

    public function articleFille()
    {
        return $this->hasMany(ArticleFille::class, 'id_article', 'id_article');
    }

    public function lot()
    {
        return $this->hasMany(Lot::class, 'id_article', 'id_article');
    }

    public function proformaFille()
    {
        return $this->hasMany(ProformaFille::class, 'id_article', 'id_article');
    }

    public function commandeFille()
    {
        return $this->hasMany(CommandeFille::class, 'id_article', 'id_article');
    }

    public function mvtStock()
    {
        return $this->hasMany(MvtStock::class, 'id_article', 'id_article');
    }

    public function proformaFournisseurFille()
    {
        return $this->hasMany(ProformaFournisseurFille::class, 'id_article', 'id_article');
    }

    public function bonCommandeFille()
    {
        return $this->hasMany(BonCommandeFille::class, 'id_article', 'id_article');
    }

    public function bonReceptionFille()
    {
        return $this->hasMany(BonReceptionFille::class, 'id_article', 'id_article');
    }

    public function bonLivraisonFille()
    {
        return $this->hasMany(BonLivraisonFille::class, 'id_article', 'id_article');
    }

    /**
     * Obtenir le prix unitaire actuel de cet article dans un magasin donné
     * en utilisant la méthode d'évaluation configurée pour l'article
     *
     * @param string $idMagasin
     * @return float
     */
    public function getPrixActuel(string $idMagasin): float
    {
        $articleService = app(\App\Services\ArticleService::class);
        
        // Utiliser la méthode d'évaluation de l'article, ou CMUP par défaut
        $methode = $this->typeEvaluation?->id_type_evaluation_stock ?? 'CMUP';
        
        return $articleService->getPrixUnitaireActuel($this->id_article, $idMagasin, $methode);
    }
}
