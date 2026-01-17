<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonLivraison extends Model
{
    use SoftDeletes;
    
    protected $table = 'bon_livraison';
    protected $primaryKey = 'id_bonLivraison';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonLivraison', 'date_', 'id_bonCommande'];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function bonLivraisonFille()
    {
        return $this->hasMany(BonLivraisonFille::class, 'id_bonLivraison', 'id_bonLivraison');
    }
}
