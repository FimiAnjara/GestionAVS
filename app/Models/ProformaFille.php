<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'proformaFille';
    protected $primaryKey = 'id_proformaFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proformaFille', 'quantite', 'id_unite', 'id_article', 'id_proforma'];

    public function proforma()
    {
        return $this->belongsTo(Proforma::class, 'id_proforma', 'id_proforma');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite', 'id_unite');
    }
}
