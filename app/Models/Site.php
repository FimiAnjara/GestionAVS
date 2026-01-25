<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;
    
    protected $table = 'site';
    protected $primaryKey = 'id_site';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_site', 'libelle', 'adresse', 'id_entite'];

    public function entite()
    {
        return $this->belongsTo(Entite::class, 'id_entite', 'id_entite');
    }

    public function magasins()
    {
        return $this->hasMany(Magasin::class, 'id_site', 'id_site');
    }

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'id_site', 'id_site');
    }
}
