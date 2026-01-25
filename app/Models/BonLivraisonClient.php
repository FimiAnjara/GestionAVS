<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonLivraisonClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_livraison_client';
    protected $primaryKey = 'id_bon_livraison_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_livraison_client', 'date_', 'description', 'id_bon_commande_client', 'id_magasin', 'id_mvt_stock', 'etat'];

    public function bonCommandeClient()
    {
        return $this->belongsTo(BonCommandeClient::class, 'id_bon_commande_client', 'id_bon_commande_client');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'id_magasin', 'id_magasin');
    }

    public function mvtStock()
    {
        return $this->belongsTo(MvtStock::class, 'id_mvt_stock', 'id_mvt_stock');
    }

    public function bonLivraisonClientFille()
    {
        return $this->hasMany(BonLivraisonClientFille::class, 'id_bon_livraison_client', 'id_bon_livraison_client');
    }
}
