<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client;
use App\Models\Magasin;

class BonCommandeClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_commande_client';
    protected $primaryKey = 'id_bon_commande_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_commande_client', 'date_', 'description', 'id_client', 'id_magasin', 'id_proforma_client', 'etat'];
    protected $casts = ['date_' => 'date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_bon_commande_client) {
                $model->id_bon_commande_client = 'BCC_' . strtoupper(uniqid());
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'id_magasin', 'id_magasin');
    }

    public function proformaClient()
    {
        return $this->belongsTo(ProformaClient::class, 'id_proforma_client', 'id_proforma_client');
    }

    public function bonCommandeClientFille()
    {
        return $this->hasMany(BonCommandeClientFille::class, 'id_bon_commande_client', 'id_bon_commande_client');
    }

    public function bonLivraisonClient()
    {
        return $this->hasMany(BonLivraisonClient::class, 'id_bon_commande_client', 'id_bon_commande_client');
    }
}
