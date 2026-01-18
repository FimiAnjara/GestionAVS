<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class FactureFournisseur extends Model
{
    use SoftDeletes;
    
    protected $table = 'factureFournisseur';
    protected $primaryKey = 'id_factureFournisseur';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_factureFournisseur', 'date_', 'etat', 'description', 'id_bonCommande'];

    protected $casts = [
        'date_' => 'datetime',
    ];

    // Accesseurs pour l'état
    public function getEtatLabelAttribute()
    {
        return match($this->etat) {
            1 => 'Créée',
            5 => 'Validée par Finance',
            11 => 'Validée par DG',
            0 => 'Annulée',
            default => 'Inconnue',
        };
    }

    public function getEtatBadgeAttribute()
    {
        return match($this->etat) {
            1 => 'warning',
            5 => 'info',
            11 => 'success',
            0 => 'danger',
            default => 'secondary',
        };
    }

    // Relations
    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function articles()
    {
        return $this->hasMany(FactureFournisseurFille::class, 'id_factureFournisseur', 'id_factureFournisseur');
    }

    public function factureFournisseurFille()
    {
        return $this->hasMany(FactureFournisseurFille::class, 'id_factureFournisseur', 'id_factureFournisseur');
    }

    public function mvtCaisse()
    {
        return $this->hasMany(MvtCaisse::class, 'id_factureFournisseur', 'id_factureFournisseur');
    }
}
