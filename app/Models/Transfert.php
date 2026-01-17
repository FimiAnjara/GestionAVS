<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfert extends Model
{
    use SoftDeletes;
    
    protected $table = 'transfert';
    protected $primaryKey = 'id_transfert';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_transfert', 'etat', 'date_', 'id_lot', 'id_emplacement'];

    public function lot()
    {
        return $this->belongsTo(Lot::class, 'id_lot', 'id_lot');
    }

    public function emplacement()
    {
        return $this->belongsTo(Emplacement::class, 'id_emplacement', 'id_emplacement');
    }
}
