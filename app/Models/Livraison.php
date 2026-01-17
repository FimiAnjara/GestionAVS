<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    protected $table = 'livraison';
    protected $primaryKey = 'id_livraison';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_livraison', 'date_', 'etat', 'id_commande'];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande', 'id_commande');
    }
}
