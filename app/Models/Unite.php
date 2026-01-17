<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    protected $table = 'unite';
    protected $primaryKey = 'id_unite';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_unite', 'libelle'];

    public function article()
    {
        return $this->hasMany(Article::class, 'id_unite', 'id_unite');
    }

    public function articleFille()
    {
        return $this->hasMany(ArticleFille::class, 'id_unite', 'id_unite');
    }

    public function proformaFille()
    {
        return $this->hasMany(ProformaFille::class, 'id_unite', 'id_unite');
    }

    public function commandeFille()
    {
        return $this->hasMany(CommandeFille::class, 'id_unite', 'id_unite');
    }

    public function mvtStock()
    {
        return $this->hasMany(MvtStock::class, 'id_unite', 'id_unite');
    }
}
