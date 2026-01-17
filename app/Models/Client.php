<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    protected $table = 'Client';
    protected $primaryKey = 'id_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_client', 'nom'];

    public function proforma()
    {
        return $this->hasMany(Proforma::class, 'id_client', 'id_client');
    }

    public function commande()
    {
        return $this->hasMany(Commande::class, 'id_client', 'id_client');
    }
}
