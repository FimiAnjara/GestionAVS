<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactureClientFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'facture_client_fille';
    protected $primaryKey = 'id_facture_client_fille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_facture_client_fille', 'id_facture_client', 'id_article', 'quantite', 'prix'];

    public function factureClient()
    {
        return $this->belongsTo(FactureClient::class, 'id_facture_client', 'id_facture_client');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function getMontantAttribute()
    {
        return $this->quantite * $this->prix;
    }
}
