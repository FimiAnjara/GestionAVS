<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonCommandeClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_commande_client';
    protected $primaryKey = 'id_bon_commande_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bon_commande_client', 'date_', 'description', 'id_client', 'id_magasin', 'id_proforma_client', 'etat'];

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
