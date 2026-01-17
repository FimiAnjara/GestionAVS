<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
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
