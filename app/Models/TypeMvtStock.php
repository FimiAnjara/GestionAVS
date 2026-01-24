<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeMvtStock extends Model
{
    use SoftDeletes;

    protected $table = 'type_mvt_stock';
    protected $primaryKey = 'id_type_mvt';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_type_mvt',
        'libelle',
        'description',
    ];
}
