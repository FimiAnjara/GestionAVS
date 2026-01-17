<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categorie';
    protected $primaryKey = 'id_categorie';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_categorie', 'libelle'];

    public function article()
    {
        return $this->hasMany(Article::class, 'id_categorie', 'id_categorie');
    }
}
