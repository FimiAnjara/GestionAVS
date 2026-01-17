<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_utilisateur', 'email', 'mdp', 'id_departement', 'id_role'];
    protected $hidden = ['mdp'];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement', 'id_departement');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function commande()
    {
        return $this->hasMany(Commande::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function bonCommande()
    {
        return $this->hasMany(BonCommande::class, 'id_utilisateur', 'id_utilisateur');
    }
}
