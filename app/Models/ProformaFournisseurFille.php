<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaFournisseurFille extends Model
{
    protected $table = 'proformaFournisseurFille';
    protected $primaryKey = 'id_proformaFornisseurFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proformaFornisseurFille', 'prix_achat', 'id_proformaFournisseur', 'id_article'];

    public function proformaFournisseur()
    {
        return $this->belongsTo(ProformaFournisseur::class, 'id_proformaFournisseur', 'id_proformaFournisseur');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
