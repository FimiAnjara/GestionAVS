<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactureFournisseurFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'factureFournisseurFille';
    protected $primaryKey = 'id_factureFournisseurFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_factureFournisseurFille', 'id_factureFournisseur', 'id_article', 'quantite', 'prix_achat'];

    protected $casts = [
        'quantite' => 'float',
        'prix_achat' => 'float',
    ];

    // Relations
    public function factureFournisseur()
    {
        return $this->belongsTo(FactureFournisseur::class, 'id_factureFournisseur', 'id_factureFournisseur');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
