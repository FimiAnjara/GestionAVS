<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use SoftDeletes;
    
    protected $table = 'categorie';
    protected $primaryKey = 'id_categorie';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_categorie', 'libelle', 'est_perissable'];

    protected $casts = [
        'est_perissable' => 'boolean',
    ];

    public function article()
    {
        return $this->hasMany(Article::class, 'id_categorie', 'id_categorie');
    }
}
