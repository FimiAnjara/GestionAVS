<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client;
use App\Models\Magasin;

class BonLivraisonClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_livraison_client';
    protected $primaryKey = 'id_bon_livraison_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_livraison_client', 'date_', 'description', 'id_client', 'id_bon_commande_client', 'id_magasin', 'etat'];
    protected $casts = ['date_' => 'date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_bon_livraison_client) {
                $model->id_bon_livraison_client = 'BLC_' . strtoupper(uniqid());
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

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
