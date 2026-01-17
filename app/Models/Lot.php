<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use SoftDeletes;
    
    protected $table = 'lot';
    protected $primaryKey = 'id_lot';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_lot', 'date_expiration', 'quantite', 'id_article'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function transfert()
    {
        return $this->hasMany(Transfert::class, 'id_lot', 'id_lot');
    }
}
