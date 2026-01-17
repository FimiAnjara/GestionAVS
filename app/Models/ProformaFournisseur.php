<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaFournisseur extends Model
{
    protected $table = 'proformaFournisseur';
    protected $primaryKey = 'id_proformaFournisseur';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proformaFournisseur', 'date_', 'etat', 'id_fournisseur'];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur', 'id_fournisseur');
    }

    public function proformaFournisseurFille()
    {
        return $this->hasMany(ProformaFournisseurFille::class, 'id_proformaFournisseur', 'id_proformaFournisseur');
    }

    public function bonCommande()
    {
        return $this->hasMany(BonCommande::class, 'id_proformaFournisseur', 'id_proformaFournisseur');
    }
}
