<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Article;

class FactureClientFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'facture_client_fille';
    protected $primaryKey = 'id_facture_client_fille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_facture_client_fille', 'id_facture_client', 'id_article', 'quantite', 'prix'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_facture_client_fille) {
                $model->id_facture_client_fille = 'FACTF_' . strtoupper(uniqid());
            }
        });
    }

    public function factureClient()
    {
        return $this->belongsTo(FactureClient::class, 'id_facture_client', 'id_facture_client');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function getMontantAttribute()
    {
        return $this->quantite * $this->prix;
    }
}
