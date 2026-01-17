<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id_role';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_role', 'libelle'];

    public function utilisateur()
    {
        return $this->hasMany(Utilisateur::class, 'id_role', 'id_role');
    }
}
