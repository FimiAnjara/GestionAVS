<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MvtStock extends Model
{
    protected $table = 'mvt_stock';
    protected $primaryKey = 'id_mvt_stock';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_mvt_stock', 'entree', 'sortie', 'date_', 'id_emplacement', 'id_article', 'id_stock'];

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
