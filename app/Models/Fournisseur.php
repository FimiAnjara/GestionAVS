<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseur';
    protected $primaryKey = 'id_fournisseur';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_fournisseur', 'nom', 'lieux'];

    public function proformaFournisseur()
    {
        return $this->hasMany(ProformaFournisseur::class, 'id_fournisseur', 'id_fournisseur');
    }
}
