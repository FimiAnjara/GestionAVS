<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emplacement extends Model
{
    use SoftDeletes;
    
    protected $table = 'emplacement';
    protected $primaryKey = 'id_emplacement';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_emplacement', 'lieux'];

    public function mvtStock()
    {
        return $this->hasMany(MvtStock::class, 'id_emplacement', 'id_emplacement');
    }

    public function transfert()
    {
        return $this->hasMany(Transfert::class, 'id_emplacement', 'id_emplacement');
    }
}
