<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    protected $table = 'caisse';
    protected $primaryKey = 'id_caisse';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_caisse', 'montant'];

    public function mvtCaisse()
    {
        return $this->hasMany(MvtCaisse::class, 'id_caisse', 'id_caisse');
    }
}
