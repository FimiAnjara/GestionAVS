<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaClient extends Model
{
    use SoftDeletes;
    
    protected $table = 'proforma_client';
    protected $primaryKey = 'id_proforma_client';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proforma_client', 'date_', 'description', 'id_client', 'id_magasin', 'etat'];

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
