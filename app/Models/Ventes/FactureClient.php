<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client;

class FactureClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'facture_client';
    protected $primaryKey = 'id_facture_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_facture_client', 'date_', 'description', 'id_client', 'id_bon_commande_client', 'etat'];
    protected $casts = ['date_' => 'date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_facture_client) {
                $model->id_facture_client = 'FACT_' . strtoupper(uniqid());
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

    public function factureClientFille()
    {
        return $this->hasMany(FactureClientFille::class, 'id_facture_client', 'id_facture_client');
    }
}
