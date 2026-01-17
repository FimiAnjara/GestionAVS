<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proforma extends Model
{
    use SoftDeletes;
    
    protected $table = 'proforma';
    protected $primaryKey = 'id_proforma';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_proforma', 'date_', 'validite', 'id_client'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function proformaFille()
    {
        return $this->hasMany(ProformaFille::class, 'id_proforma', 'id_proforma');
    }
}
