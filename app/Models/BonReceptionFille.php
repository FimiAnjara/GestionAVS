<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonReceptionFille extends Model
{
    use SoftDeletes;
    
    protected $table = 'bonReceptionFille';
    protected $primaryKey = 'id_bonReceptionFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_bonReceptionFille', 'quantite', 'id_bonReception', 'id_article'];

    public function bonReception()
    {
        return $this->belongsTo(BonReception::class, 'id_bonReception', 'id_bonReception');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
