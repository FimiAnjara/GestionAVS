<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Article;

class BonCommandeClientFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_commande_client_fille';
    protected $primaryKey = 'id_bon_commande_client_fille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_commande_client_fille', 'id_bon_commande_client', 'id_article', 'quantite', 'prix'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_bon_commande_client_fille) {
                $model->id_bon_commande_client_fille = 'BCCF_' . strtoupper(uniqid());
            }
        });
    }

    public function bonCommandeClient()
    {
        return $this->belongsTo(BonCommandeClient::class, 'id_bon_commande_client', 'id_bon_commande_client');
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
