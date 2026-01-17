<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleFille extends Model
{
    protected $table = 'articleFille';
    protected $primaryKey = 'id_articleFille';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id_articleFille', 'prix', 'date_', 'quantite', 'id_unite', 'id_article'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite', 'id_unite');
    }
}
