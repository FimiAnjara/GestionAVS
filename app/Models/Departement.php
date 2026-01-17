<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departement extends Model
{
    use SoftDeletes;
    
    protected $table = 'departement';
    protected $primaryKey = 'id_departement';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_departement', 'libelle'];

    public function utilisateur()
    {
        return $this->hasMany(Utilisateur::class, 'id_departement', 'id_departement');
    }
}
