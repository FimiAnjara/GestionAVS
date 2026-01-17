<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonCommandeFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'bonCommandeFille';
    protected $primaryKey = 'id_bonCommandeFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonCommandeFille', 'quantite', 'id_bonCommande', 'id_article'];

    public function bonCommande()
    {
        return $this->belongsTo(BonCommande::class, 'id_bonCommande', 'id_bonCommande');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
