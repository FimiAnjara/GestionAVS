<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Article;

class ProformaClientFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'proforma_client_fille';
    protected $primaryKey = 'id_proforma_client_fille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proforma_client_fille', 'id_proforma_client', 'id_article', 'quantite', 'prix'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_proforma_client_fille) {
                $model->id_proforma_client_fille = 'PROFF_' . strtoupper(uniqid());
            }
        });
    }

    public function proformaClient()
    {
        return $this->belongsTo(ProformaClient::class, 'id_proforma_client', 'id_proforma_client');
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
