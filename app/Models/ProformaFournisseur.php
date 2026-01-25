<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaFournisseur extends Model
{
    use SoftDeletes;
    
    protected $table = 'proformaFournisseur';
    protected $primaryKey = 'id_proformaFournisseur';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proformaFournisseur', 'date_', 'etat', 'description', 'id_fournisseur', 'id_magasin'];

    protected $casts = [
        'date_' => 'datetime',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id_fournisseur');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'id_magasin', 'id_magasin');
    }

    public function proformaFournisseurFille()
    {
        return $this->hasMany(ProformaFournisseurFille::class, 'id_proformaFournisseur', 'id_proformaFournisseur');
    }

    public function getEtatLabelAttribute()
    {
        $etats = [
            1 => 'Créée',
            5 => 'Validée par Finance',
            11 => 'Validée par DG',
            0 => 'Annulée',
        ];
        return $etats[$this->etat] ?? 'Inconnu';
    }

    public function getEtatBadgeAttribute()
    {
        $badges = [
            1 => 'warning',
            5 => 'info',
            11 => 'success',
            0 => 'danger',
        ];
        return $badges[$this->etat] ?? 'secondary';
    }

    public function bonCommande()
    {
        return $this->hasMany(BonCommande::class, 'id_proformaFournisseur', 'id_proformaFournisseur');
    }
}
