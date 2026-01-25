<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonLivraisonClientFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_livraison_client_fille';
    protected $primaryKey = 'id_bon_livraison_client_fille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_livraison_client_fille', 'id_bon_livraison_client', 'id_article', 'quantite'];

    public function bonLivraisonClient()
    {
        return $this->belongsTo(BonLivraisonClient::class, 'id_bon_livraison_client', 'id_bon_livraison_client');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
