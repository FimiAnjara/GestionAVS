<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Magasin extends Model
{
    use SoftDeletes;
    
    protected $table = 'magasin';
    protected $primaryKey = 'id_magasin';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = ['id_magasin', 'nom', 'longitude', 'latitude'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];
}
