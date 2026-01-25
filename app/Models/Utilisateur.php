<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Utilisateur extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasFactory, Notifiable;
    
    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_utilisateur', 'email', 'mdp', 'id_departement', 'id_role', 'id_entite', 'id_site', 'id_magasin'];
    protected $hidden = ['mdp'];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->mdp;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'role' => $this->role?->libelle,
            'departement' => $this->departement?->libelle,
        ];
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement', 'id_departement');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'id_magasin', 'id_magasin');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'id_site', 'id_site');
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class, 'id_entite', 'id_entite');
    }

    public function commande()
    {
        return $this->hasMany(Commande::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function bonCommande()
    {
        return $this->hasMany(BonCommande::class, 'id_utilisateur', 'id_utilisateur');
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role)
    {
        return $this->role && strtolower(str_replace(' ', '_', $this->role->libelle)) === strtolower(str_replace(' ', '_', $role));
    }

    /**
     * Vérifier si l'utilisateur a un parmi plusieurs rôles
     */
    public function hasAnyRole($roles)
    {
        $roles = is_array($roles) ? $roles : explode('|', $roles);
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier si l'utilisateur est dans un département spécifique
     */
    public function hasPermissionIn($department)
    {
        return $this->departement && strtolower(str_replace(' ', '_', $this->departement->libelle)) === strtolower(str_replace(' ', '_', $department));
    }
}
