<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use SoftDeletes;
    
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
