<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MvtCaisse extends Model
{
    protected $table = 'mvt_caisse';
    protected $primaryKey = 'id_mvt_caisse';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_mvt_caisse', 'origine', 'debit', 'credit', 'description', 'date_', 'id_caisse'];

    public function caisse()
    {
        return $this->belongsTo(Caisse::class, 'id_caisse', 'id_caisse');
    }
}
