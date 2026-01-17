<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonLivraisonFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'bonLivraisonFille';
    protected $primaryKey = 'id_bonLivraisonFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonLivraisonFille', 'quantite', 'id_bonLivraison', 'id_article'];

    public function bonLivraison()
    {
        return $this->belongsTo(BonLivraison::class, 'id_bonLivraison', 'id_bonLivraison');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
