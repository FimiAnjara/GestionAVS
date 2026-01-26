<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MvtStockFille extends Model
{
    use SoftDeletes;
    protected $table = 'mvt_stock_fille';
    protected $primaryKey = 'id_mvt_stock_fille';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = ['id_mvt_stock_fille', 'id_mvt_stock', 'id_article', 'id_mvt_source', 'entree', 'sortie', 'prix_unitaire', 'reste', 'date_expiration'];

    protected $casts = [
        'date_expiration' => 'date',
    ];

    public function mvtStock()
    {
        return $this->belongsTo(MvtStock::class, 'id_mvt_stock', 'id_mvt_stock');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function sourceMovement()
    {
        return $this->belongsTo(MvtStockFille::class, 'id_mvt_source', 'id_mvt_stock_fille');
    }

    public function derivativeMovements()
    {
        return $this->hasMany(MvtStockFille::class, 'id_mvt_source', 'id_mvt_stock_fille');
    }

    public function getMontantAttribute()
    {
        $vrai_quantite = ($this->entree ?? 0) + ($this->sortie ?? 0);
        return $vrai_quantite * ($this->prix_unitaire ?? 0);
    }
}
