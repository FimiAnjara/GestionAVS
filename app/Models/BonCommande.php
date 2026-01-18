<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonCommande extends Model
{
    use SoftDeletes;
    
    protected $table = 'bonCommande';
    protected $primaryKey = 'id_bonCommande';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonCommande', 'date_', 'etat', 'id_utilisateur', 'id_proformaFournisseur', 'id_factureFournisseur'];

    protected $casts = [
        'date_' => 'datetime',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function proformaFournisseur()
    {
        return $this->belongsTo(ProformaFournisseur::class, 'id_proformaFournisseur', 'id_proformaFournisseur');
    }

    public function fournisseur()
    {
        return $this->hasOneThrough(
            Fournisseur::class,
            ProformaFournisseur::class,
            'id_proformaFournisseur',
            'id_fournisseur',
            'id_proformaFournisseur',
            'id_fournisseur'
        );
    }

    public function bonCommandeFille()
    {
        return $this->hasMany(BonCommandeFille::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function bonReception()
    {
        return $this->hasMany(BonReception::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function bonLivraison()
    {
        return $this->hasMany(BonLivraison::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function factureFournisseur()
    {
        return $this->belongsTo(FactureFournisseur::class, 'id_factureFournisseur', 'id_factureFournisseur');
    }
}
