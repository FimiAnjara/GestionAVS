<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commande';
    protected $primaryKey = 'id_commande';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_commande', 'date_', 'etat', 'id_utilisateur', 'id_client'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function commandeFille()
    {
        return $this->hasMany(CommandeFille::class, 'id_commande', 'id_commande');
    }

    public function livraison()
    {
        return $this->hasMany(Livraison::class, 'id_commande', 'id_commande');
    }
}
