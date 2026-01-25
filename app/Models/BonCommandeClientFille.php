<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonCommandeClientFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_commande_client_fille';
    protected $primaryKey = 'id_bon_commande_client_fille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_commande_client_fille', 'id_bon_commande_client', 'id_article', 'quantite', 'prix'];

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
