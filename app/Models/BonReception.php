<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonReception extends Model
{
    use SoftDeletes;
    
    protected $table = 'bonReception';
    protected $primaryKey = 'id_bonReception';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonReception', 'date_', 'id_bonCommande', 'etat'];

    protected $casts = [
        'date_' => 'datetime',
    ];

    public function getEtatLabelAttribute()
    {
        return match($this->etat) {
            1 => 'Créée',
            11 => 'Réceptionnée',
            0 => 'Annulée',
            default => 'Inconnu'
        };
    }

    public function getEtatBadgeAttribute()
    {
        return match($this->etat) {
            1 => 'warning',
            11 => 'success',
            0 => 'danger',
            default => 'secondary'
        };
    }

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function bonReceptionFille()
    {
        return $this->hasMany(BonReceptionFille::class, 'id_bonReception', 'id_bonReception');
    }
}
