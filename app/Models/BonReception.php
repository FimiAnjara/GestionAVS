<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonReception extends Model
{
    protected $table = 'bonReception';
    protected $primaryKey = 'id_bonReception';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonReception', 'date_', 'id_bonCommande'];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function bonReceptionFille()
    {
        return $this->hasMany(BonReceptionFille::class, 'id_bonReception', 'id_bonReception');
    }
}
