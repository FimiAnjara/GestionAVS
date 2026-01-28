<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MvtStock extends Model
{
    use SoftDeletes;
    
    protected $table = 'mvt_stock';
    protected $primaryKey = 'id_mvt_stock';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_mvt_stock', 'date_', 'id_magasin', 'id_type_mvt', 'montant_total', 'description'];

    protected $casts = [
        'date_' => 'datetime',
    ];

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'id_magasin', 'id_magasin');
    }

    public function typeMvt()
    {
        return $this->belongsTo(TypeMvtStock::class, 'id_type_mvt', 'id_type_mvt');
    }

    public function mvtStockFille()
    {
        return $this->hasMany(MvtStockFille::class, 'id_mvt_stock', 'id_mvt_stock');
    }

    public function mvtStockFilles()
    {
        return $this->hasMany(MvtStockFille::class, 'id_mvt_stock', 'id_mvt_stock');
    }
}
