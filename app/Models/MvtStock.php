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

    protected $fillable = ['id_mvt_stock', 'entree', 'sortie', 'date_', 'id_emplacement', 'id_article', 'id_stock', 'id_bonCommande', 'id_bonReception', 'date_expiration'];

    protected $casts = [
        'date_' => 'datetime',
        'date_expiration' => 'date',
    ];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function bonReception()
    {
        return $this->belongsTo(BonReception::class, 'id_bonReception', 'id_bonReception');
    }

    public function emplacement()
    {
        return $this->belongsTo(Emplacement::class, 'id_emplacement', 'id_emplacement');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'id_stock', 'id_stock');
    }
}
