<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entite extends Model
{
    use SoftDeletes;
    
    protected $table = 'entite';
    protected $primaryKey = 'id_entite';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_entite', 'libelle', 'adresse', 'telephone', 'email'];

    public function sites()
    {
        return $this->hasMany(Site::class, 'id_entite', 'id_entite');
    }

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'id_entite', 'id_entite');
    }
}
