<?php

namespace App\Models\Ventes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client;
use App\Models\Magasin;

class ProformaClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'proforma_client';
    protected $primaryKey = 'id_proforma_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proforma_client', 'date_', 'description', 'id_client', 'id_magasin', 'etat'];
    protected $casts = ['date_' => 'date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id_proforma_client) {
                $model->id_proforma_client = 'PROF_' . strtoupper(uniqid());
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'id_magasin', 'id_magasin');
    }

    public function proformaClientFille()
    {
        return $this->hasMany(ProformaClientFille::class, 'id_proforma_client', 'id_proforma_client');
    }
}
