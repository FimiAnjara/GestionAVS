<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;
    
    protected $table = 'stock';
    protected $primaryKey = 'id_stock';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_stock', 'origine', 'date_', 'description', 'etat'];

    public function mvtStock()
    {
        return $this->hasMany(MvtStock::class, 'id_stock', 'id_stock');
    }
}
